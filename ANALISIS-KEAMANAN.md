# Analisis Keamanan & Kesesuaian API — `agumil/satusehat-sdk` (v3)

**Tanggal:** 28 Oktober 2026
**Commit yang dianalisis:** `abf9295` — *ci: tambah workflow GitHub Actions dan naikkan Guzzle ke 7.16*
**Metode:** pembacaan kode + eksekusi nyata (Guzzle MockHandler, server lokal, reflection, probe jaringan). Setiap klaim di bawah punya output yang bisa direproduksi.

> **Riwayat:** v1 (commit `5353959`) sudah kedaluwarsa. v2 (`eafaddc`) menemukan 4 temuan P0 yang kemudian diperbaiki. v3 menilai kode **setelah** perbaikan itu, dan sengaja menyerang balik perbaikan tersebut.

---

## 1. Ringkasan Eksekutif

**Skor kesiapan produksi: 8,5/10** (v2: 7/10, v1: 4/10).

Empat temuan P0 dari v2 sudah tertutup dan **dibuktikan**, bukan sekadar diubah: endpoint token kini punya timeout, PHI disamarkan dengan pendekatan default-deny, `X-Debug-Mode` ditolak di production, dan file token diverifikasi permission serta ditandatangani HMAC. Ditambah: 43 test, CI 4 job hijau, dan 13 advisory dependensi hilang.

Yang masih menghalangi produksi bukan lagi "bocor" atau "berhenti", melainkan **kelengkapan kontrak** dan **ketahanan saat jaringan bermasalah**:

| # | Temuan | Tingkat |
|---|---|---|
| **N12** | Tidak ada anggaran waktu total untuk retry: `Retry-After` dihormati per percobaan, tanpa plafon keseluruhan | MEDIUM |
| **N10** | 26 method `update*` fatal (`Error`, bukan exception SDK) bila diberi builder, padahal `create*` menerimanya | MEDIUM |
| **N11** | `HttpRequest` tanpa `connect_timeout` — kegagalan koneksi memakan seluruh `timeout` (default 60 dtk) | LOW–MEDIUM |
| **F3** | 22 resource resmi belum ada (Task, DocumentReference, Coverage/Claim, dll.) | MEDIUM (fitur) |

---

## 2. Verifikasi Perbaikan P0 (v2 → v3)

Semua diuji ulang di kode saat ini, bukan dipercaya dari pesan commit.

| Temuan v2 | Status | Bukti di commit `abf9295` |
|---|---|---|
| N1 — endpoint token tanpa timeout, exception Guzzle bocor | ✅ | Server lokal menahan 8 dtk: dengan `timeout=2` berhenti di **2,1 dtk** dan melempar `SSOauth2Exception`. 5xx/`ConnectException` kini juga `SSOauth2Exception`, `client_secret` disaring dari pesan. |
| N2 — `PhiRedactor` membocorkan PHI | ✅ | `subject.display`, `text.div`, `note.text` → `[REDACTED]`; id di path `/Patient/100000030009` → `/Patient/[REDACTED]`. Test integrasi memastikan payload nyata tidak membawa nama/NIK ke logger. |
| N3 — `X-Debug-Mode` KYC di production | ✅ | `SSEnvException` dilempar **sebelum** request dibuat (`calls === []`). Header tetap terkirim di staging/development. |
| N4 — `FileTokenStore` permission & integritas | ✅ | Mode diverifikasi `fileperms()`; file palsu dan file bertanda tangan kunci lain ditolak; `umask(0077)` menutup jendela 0644. |
| N5 — `BundlePager::isFinished()` | ✅ | Loop berhenti tepat di `max_pages`. |
| N9 — tidak ada test/CI, advisory dependensi | ✅ sebagian | 43 test (78 assertion), CI 4 job **hijau di run pertama** (7/7 job sukses), Guzzle 7.16.1 + psr7 2.13.2 → `composer audit` bersih. Sisa N9 di §4. |

**Verifikasi silang di CI** (run `40657721031`): PHP 8.0, 8.1, 8.2, 8.3, 8.4, *lowest dependencies*, dan *dependency audit* — semuanya `success`.

---

## 3. Temuan Baru (hasil menyerang balik perbaikan v2)

### N10 — `update*` fatal bila diberi builder, `create*` tidak (MEDIUM)

26 resource memakai pola `$params['id'] = $id;` pada method `update*`, sementara parameter `$params` tidak diberi tipe:

```
signature updateOrganization: id:string  params:none
$params = $builder;
$params['id'] = 'x';
→ Error: Cannot use object of type ...PayloadBuilderOrganization as array
```

Dua masalah sekaligus:

1. **Inkonsisten.** `createOrganization($builder)` berjalan karena `HttpRequest::initializeOptions()` mendeteksi objek ber-`build()`. Pengguna yang memakai pola builder di semua tempat akan mendapat `Error` mendadak hanya saat update.
2. **Jenis kegagalannya salah.** Yang dilempar `\Error` mentah, bukan `SSDataTypeException`/`SSEnvException`. `catch (\Exception)` di aplikasi tidak menangkapnya, sehingga muncul sebagai fatal error 500.

Catatan: contoh di `examples/FHIR/Organization/put.php` memanggil `->build()` lebih dulu, jadi jalur yang didokumentasikan aman — yang rusak adalah jalur yang wajar diasumsikan pengguna.

**Perbaikan yang disarankan:** terima `array|object`, lalu normalisasi dengan
`$payload = is_object($params) && method_exists($params, 'build') ? $params->build() : $params;`
sebelum menambahkan `id`. Satu helper, 26 pemanggil.

### N11 — `connect_timeout` belum ada di jalur FHIR (LOW–MEDIUM)

`Oauth2` kini punya `timeout` **dan** `connect_timeout`, tetapi `HttpRequest` hanya mengirim `timeout`:

```
opsi yang dikirim HttpRequest: {"timeout":3}
```

Terukur ke alamat yang di-blackhole (`192.0.2.1`):

```
HTTP 0 | durasi 3.0 dtk | {"errno":28,"error":"Connection timed out after 3012 milliseconds"}
```

Jadi **tidak** menggantung tanpa batas — `timeout` cURL mencakup fase connect. Yang menjadi masalah adalah skalanya: dengan default 60 dtk, satu kegagalan koneksi menahan worker PHP-FPM selama 60 dtk, padahal connect gagal biasanya sudah pasti dalam 5 dtk. Selama gangguan jaringan, ini mengubah satu host yang tidak terjangkau menjadi worker pool yang habis.

**Perbaikan:** tambahkan `connect_timeout` (default 5) ke `initializeOptions()` dan `Config\Connection`, sejajar dengan `Oauth2`.

### N12 — Retry tanpa anggaran total (MEDIUM)

`Retry-After` dihormati per percobaan, tetapi tidak ada plafon keseluruhan:

```
2x Retry-After: 2  →  durasi 4.0 dtk  (status akhir 200)
```

Dengan `retry_attempts = 3` dan `Retry-After: 60` (nilai yang sah untuk 429), satu panggilan dapat menahan worker hingga ~120 detik, dan itu terjadi per request. Saat SATUSEHAT membalas 429 massal, aplikasi justru menambah tekanan: setiap worker tidur 60 detik sambil memegang koneksi.

**Perbaikan:** tambahkan `retry_total_budget_ms` (mis. 30.000) yang diperiksa sebelum `usleep()`; bila anggaran habis, kembalikan respons terakhir apa adanya alih-alih menunggu lagi.

### Yang saya curigai tetapi ternyata aman — jangan "diperbaiki"

Ini sama pentingnya dengan temuan, karena perubahan di sini akan merusak sesuatu yang sudah benar:

| Dugaan | Hasil verifikasi |
|---|---|
| Redirect 302 bisa membocorkan Bearer token ke host lain | **Aman.** Guzzle membuang `Authorization` pada redirect lintas origin — terukur: request ke `attacker.example` terkirim **tanpa** header Authorization. Tidak perlu mematikan `allow_redirects`. |
| `updatePatient()` salah `Content-Type` (RFC 6902 meminta `application/json-patch+json`) | **Aman.** Dokumentasi Patient menyatakan `application/json` **WAJIB**, dan SDK mengirim tepat itu. Mengikuti RFC justru akan merusak. |
| `PATCH` tidak ikut di-retry (N7 di v2) | **Bukan bug.** JSON Patch dengan `op: add` tidak idempoten; mengulangnya bisa menggandakan data. Pengecualian PATCH dari `retry_methods` sudah benar. |
| `MemoryLogger` di test pecah pada `psr/log` 1.x | **Aman.** Parameter `$message` sengaja tanpa tipe; 43 test lulus dengan `psr/log 1.1.0` (job `lowest`). |
| `Practitioner` tanpa `create`/`update` | **Bukan kelalaian.** API resmi memang GET-only. |

---

## 4. Yang Masih Terbuka

| # | Celah | Dampak |
|---|---|---|
| **F3** | **22 resource resmi belum ada**: Account, BillingStatus, ChargeItem, ChargeItemDefinition, ChargeItemResponse, Claim, ClaimResponse, Coverage, CoverageEligibilityRequest/Response, DocumentReference, Endpoint, Goal, Invoice, MedicationAdministration, MedicationStatement, NutritionOrder, PaymentNotice, PaymentReconciliation, RiskAssessment, Substance, Task | Untuk use case rujukan pasien: `Task` (status rujukan), `DocumentReference` (lampiran), `Coverage`/`Claim` (jaminan) belum bisa dikirim |
| **F7** | Tanpa `If-Match`/`meta.versionId` | Rawan *lost update* saat dua sistem menulis resource yang sama |
| **F10** | Kombinasi parameter pencarian `Patient` tidak divalidasi | Salah pakai baru terdeteksi sebagai 400 dari server |
| **F14** | `Terminology/` hanya konstanta lokal, tanpa `$validate-code`/`$lookup` | Validasi kode ke kamus resmi harus dibangun sendiri |
| **N6** | `EnvHelper::__construct()` adalah kode mati yang menunjuk `.env` di `src/Helper` | Jebakan bila suatu saat dipanggil; sebaiknya dihapus |
| **N9** | `"minimum-stability": "dev"` masih ada; belum ada PHPStan/Psalm | Sudah terbukti berbahaya: `composer update` sempat memilih `guzzle 7.7.x-dev` saat menaikkan versi |
| **N13** | `HttpRequest` membuat `Client` baru tiap request | Tidak ada connection reuse → TLS handshake berulang; murni performa, bukan keamanan |

**Cakupan saat ini:** 29 resource FHIR (semua dengan search/byId/create/update kecuali `Practitioner` yang GET-only), plus `Consent`, `KYC`, dan `KFA`. 119 method publik, **semuanya** terjangkau dari `SSClient`/`SSClientKYC` — tidak ada method yang "ada tapi tidak bisa dipanggil".

---

## 5. Prioritas Berikutnya

**P1 — sebelum dipakai untuk rujukan pasien**

1. N10 — `update*` menerima builder (26 resource, satu helper).
2. N12 — anggaran total retry.
3. F3 sebagian — minimal `Task` dan `DocumentReference`.

**P2 — ketahanan & higiene**

4. N11 — `connect_timeout` di `HttpRequest`.
5. F7 — `If-Match` opsional.
6. N9 — hapus `minimum-stability: dev`, tambahkan PHPStan level 5 ke CI.
7. N6, N13.

---

## 6. Lampiran — Cara Reproduksi

```bash
composer install
composer test          # 43 test, 78 assertion, tanpa jaringan
composer audit         # 0 advisory
```

```
### 1. Timeout token (server lokal menahan 8 dtk)
timeout=2        2,1 dtk | SSOauth2Exception: Unable to reach token endpoint
default 15s     14,0 dtk | token didapat

### 2. Redaksi PHI
subject.display : "[REDACTED]"
text.div        : "[REDACTED]"
note.text       : "[REDACTED]"
/Patient/100000030009 -> /Patient/[REDACTED]

### 3. KYC production
SSEnvException: 'X-Debug-Mode' is only available in development or staging.

### 4. FileTokenStore
file tanpa mac / mac kunci lain  -> get() = null
chmod tidak berefek (Windows)    -> SSEnvException "instead of 0600"

### 5. Retry
2x Retry-After: 2  -> 4,0 dtk (tanpa plafon total)

### 6. Koneksi blackhole
timeout=3 -> HTTP 0 dalam 3,0 dtk (bounded, bukan menggantung)

### 7. Redirect lintas host
request 1 -> api-satusehat... Authorization: Bearer t
request 2 -> attacker.example  Authorization: (tidak ada)

### 8. update* dengan builder
Error: Cannot use object of type PayloadBuilderOrganization as array
```

**Batas kejujuran analisis ini:** dua test permission POSIX di-skip di mesin ini (Windows), dan WSL diblokir kebijakan sandbox — jalur `chmod`/`fileperms` diuji lewat simulasi `PosixFileTokenStore`, bukan syscall asli. Di CI Linux keduanya berjalan sungguhan. Perbandingan endpoint tetap bersandar pada katalog resmi `satusehat.kemkes.go.id/platform/docs/id/api-catalogue/`; JSON koleksi Postman tidak dapat diunduh tanpa API key.
