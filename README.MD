
<h4 align="center">Unofficial API wrapper package to integrate with SATUSEHAT platform.</h4>

<p align="center">
  <a href="#note">Note</a> •
  <a href="#key-features">Key Features</a> •
  <a href="#installation">Installation</a> •
  <a href="#quick-start">Quick Start</a> •
  <a href="#configuration">Configuration</a> •
  <a href="#partial-update">Partial Update</a> •
  <a href="#paging">Paging</a> •
  <a href="#kyc">KYC</a> •
  <a href="#logging">Logging</a> •
  <a href="#token-caching">Token Caching</a> •
  <a href="#license">License</a>
</p>

![screenshot](https://d290ny10omyv12.cloudfront.net/images/satusehat-og-fb.png)

## Note

This package is on-progress, documentation is very limited AND you may find out that several components are incomplete. Please try this on your Development Environment first to prevent any possible break to your project.

## Key Features

* Payload Builder
  - Build your payload as consistent as possible with payload builder
  - Don't like it ? You could ignore this as well
* Keep it simple
  - Less 3rd party library, only required the necessary one
  - Resource class that fulfill HL7 FHIR standard
* Flexible
  - Not constrained with specific framework
  - Send your payload however you like to match your project
* Resilient by default
  - Automatic retry for `429`/`5xx` with `Retry-After` support and exponential backoff
  - Only idempotent methods are retried, so a `POST` is never silently duplicated
  - The token endpoint has its own `timeout`/`connect_timeout` (15s/5s by default) and every transport failure surfaces as `SSOauth2Exception`
* Safe by default
  - Resource ids are validated before being interpolated into URLs
  - PHI is redacted before anything reaches your log
* Practical helpers
  - `BundlePager` to walk FHIR searchset pages without touching `_page_token`
  - Optional shared token store so PHP-FPM workers reuse one access token
* Support php >= 8.0

## Installation

To run this library, you'll need PHP 8.0+, and Composer. From your command line:

> **Requirements:** `guzzlehttp/guzzle` 7.16+ and `guzzlehttp/psr7` 2.13+. Guzzle 6 is no longer supported — it is end-of-life and has advisories without fixes. Guzzle 8 is not supported yet: it requires `psr7` 3 and `promises` 3, which is a separate migration.

```bash
# install this package
$ composer require agumil/satusehat-sdk
```

You could save your SATUSEHAT ENVIRONMENT, CLIENT ID, and CLIENT_SECRET to your .env file(s). From your command line:
```bash
# install recommended library
$ composer require vlucas/phpdotenv
```

Supported ENV:
```bash
# development | staging | production
SATUSEHAT_ENV=development|staging|production
SATUSEHAT_ORGANIZATION_ID=organization_id
SATUSEHAT_CLIENT_ID=client_id
SATUSEHAT_CLIENT_SECRET=client_secret
```

`SATUSEHAT_ENV` menentukan host untuk seluruh modul (FHIR, Consent, KFA, KYC, dan OAuth2): `development` → `api-satusehat-dev.dto.kemkes.go.id`, `staging` → `api-satusehat-stg.dto.kemkes.go.id`, `production` → `api-satusehat.kemkes.go.id`. Nilai `.env` didahulukan; parameter `'environment'` pada config hanya dipakai bila `.env` tidak diisi.

> **Note**
> Logging is optional. The SDK depends on `psr/log` (interfaces only) but writes
> nothing unless you pass a logger instance. Any PSR-3 logger works:
> `composer require monolog/monolog` if you don't have one yet.

## Quick Start
With PHPDOTENV
```php
use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\SSClient;

$ssclient = new SSClient(new Oauth2());
$response = $ssclient->getPractitioner([
    'identifier' => 'https://fhir.kemkes.go.id/id/nakes-his-number|10009880728',
]);
```

Without PHPDOTENV
```php
use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\SSClient;

$config1['environment'] = 'development';
$config1['client_id'] = 'your_client_id';
$config1['client_secret'] = 'your_client_secret';

$config2['environment'] = 'development';

$ssclient = new SSClient(new Oauth2($config1), $config2);
$response = $ssclient->getPractitioner([
    'identifier' => 'https://fhir.kemkes.go.id/id/nakes-his-number|10009880728',
]);
```

Host ditentukan oleh `environment`, bukan oleh URL yang Anda masukkan. Tidak ada kunci `base_url`: bila URL bisa diatur pemanggil, satu payload yang salah dapat mengarahkan Bearer token ke host lain (SSRF).

## Configuration

The second argument of `SSClient` is forwarded to every request. All keys are optional, and the defaults are safe to use as-is.

```php
use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\SSClient;

$ssclient = new SSClient(new Oauth2(), [
    'timeout' => 30,
    'logger' => $logger,
    'retry_attempts' => 3,
    'retry_base_delay_ms' => 200,
    'retry_max_delay_ms' => 5000,
    'retry_methods' => ['GET', 'PUT', 'DELETE'],
]);
```

| Key | Type | Default | Description |
| --- | --- | --- | --- |
| `timeout` | `int` | `60` | Request timeout in seconds. |
| `logger` | `Psr\Log\LoggerInterface` | `null` | Optional PSR-3 logger. Without it, nothing is logged. |
| `retry_attempts` | `int` | `3` | Total attempts including the first one. Set to `1` to disable retrying. |
| `retry_base_delay_ms` | `int` | `200` | Base delay for exponential backoff. |
| `retry_max_delay_ms` | `int` | `5000` | Upper bound for the computed backoff delay. |
| `retry_methods` | `string[]` | `['GET', 'PUT', 'DELETE']` | HTTP methods eligible for retry. |

### Retry behaviour

A request is retried only when both conditions hold:

1. The response status is `429`, `500`, `502`, `503`, or `504`.
2. The HTTP method is listed in `retry_methods`.

Other `4xx` responses (`400`, `401`, `403`, `404`) are never retried, because repeating them produces the same answer. `POST` is excluded by default: if the first request actually succeeded but the response was lost, retrying it would create the resource twice. Add `'POST'` to `retry_methods` only when your endpoint is safe to duplicate.

When the server sends a `Retry-After` header, its value is honoured (capped at 60 seconds). Otherwise the SDK waits `retry_base_delay_ms * 2^(attempt - 1)` with jitter, never exceeding `retry_max_delay_ms`. After `retry_attempts` is exhausted, the last response is returned to you as-is — it is not turned into an exception, so `$response->getHttpCode()` and `$response->getErrorMessages()` still work.

## Partial Update

`createPatient()` always sends the entire resource. To change a single field without resending everything, use `updatePatient()`, which sends a JSON Patch document ([RFC 6902](https://datatracker.ietf.org/doc/html/rfc6902)):

```php
$response = $ssclient->updatePatient('P02478375538', [
    [
        'op' => 'replace',
        'path' => '/language',
        'value' => 'id',
    ],
]);

var_dump($response->getContentAsObject());
```

SATUSEHAT currently accepts only the `replace` operation. Multiple operations can be sent in one array, and each `path` is relative to the resource root (for example `/name/0/text`).

## Paging

A FHIR search returns a `Bundle` whose `link.next` contains an opaque `_page_token`. You cannot compute the next page yourself — you must resend the parameters from that link. `BundlePager` does it for you:

```php
use agumil\SatuSehatSDK\Helper\BundleHelper;
use agumil\SatuSehatSDK\Helper\BundlePager;

$pager = new BundlePager(
    fn (array $params) => $ssclient->getPatient($params),
    ['identifier' => 'https://fhir.kemkes.go.id/id/nik|9271060312000001', '_count' => 100]
);

while (($response = $pager->next()) !== null) {
    foreach (BundleHelper::entries($response->getContentAsArray()) as $entry) {
        echo $entry['resource']['id'] . PHP_EOL;
    }
}

echo 'Pages fetched: ' . $pager->page() . PHP_EOL;
```

`next()` returns `null` once there is no further page. It also stops when a request fails, or when the server hands back the same link twice (a guard against endless loops). The optional third constructor argument caps the number of pages, defaulting to `1000`.

`BundleHelper` is useful on its own when you already have a decoded Bundle:

```php
BundleHelper::isBundle($content);        // bool
BundleHelper::entries($bundle);          // list of entries, or []
BundleHelper::total($bundle);            // int|null
BundleHelper::nextUrl($bundle);          // string|null
BundleHelper::nextParams($bundle);       // array|null of query params
```

## KYC

KYC (verifikasi NIK) punya base URL sendiri (`/kyc/v1`) dengan Bearer token yang sama, jadi ia dipakai lewat client terpisah: `SSClientKYC`.

Alurnya tiga langkah, dan SDK hanya mengerjakan dua di antaranya — enkripsi dan dekripsi tetap di sisi aplikasi karena kuncinya dipegang faskes:

1. **Enkripsi** pesan permintaan memakai kunci publik dari Kemkes (di luar SDK).
2. `generateUrl()` menukar pesan terenkripsi itu dengan sesi KYC. Responsnya masih ciphertext; dekripsikan untuk mengambil `url_token`.
3. `generateChallengeCode()` atau `requestPerNik()` meminta challenge code memakai `url_token` tersebut.

```php
use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\SSClientKYC;

$kyc = new SSClientKYC(new Oauth2(), ['environment' => 'development']);

$url_response = $kyc->generateUrl($encrypted_message); // text/plain, masih ciphertext
$url_token = /* hasil dekripsi di sisi aplikasi */;

$response = $kyc->requestPerNik('3171022809990001', 'Budi Santoso', $url_token);

if ($response->getHttpCode() === 200) {
    $data = $response->getContentAsObject()->data;

    echo $data->ihs_number;        // IHS Number pasien pemilik NIK
    echo $data->challenge_code;    // 6 digit
    echo $data->expired_timestamp; // batas waktu challenge code
}
```

| Method | Endpoint | Keterangan |
| --- | --- | --- |
| `generateUrl(string $encryptedMessage)` | `POST /kyc/v1/generate-url` | Body dikirim apa adanya sebagai `text/plain`. |
| `generateChallengeCode(array $params, string $urlToken, bool $debugMode = false)` | `POST /kyc/v1/challenge-code` | `$params` berisi `['metadata' => [...], 'data' => [...]]`. |
| `requestPerNik(string $nik, string $name, string $urlToken, bool $debugMode = false)` | `POST /kyc/v1/challenge-code` | Pintasan dengan `metadata.method = request_per_nik`. |

Beberapa hal yang perlu diketahui:

- `$urlToken` dikirim sebagai header `X-Frame-Token`. SDK tidak menyimpannya, jadi ambil dari respons `generate-url()` lalu teruskan sendiri.
- `$debugMode = true` menambahkan `X-Debug-Mode: 1` supaya challenge code dikembalikan langsung tanpa widget KYC. Pakai hanya di development/staging: mode ini melewati verifikasi yang seharusnya dilakukan pasien, jadi SDK menolaknya sendiri (`SSEnvException`) bila environment-nya `production` — request tidak dikirim sama sekali.
- NIK diperiksa 16 digit sebelum dikirim, karena respons memuat `ihs_number` milik NIK tersebut — salah ketik berarti challenge code untuk orang lain.
- Lingkungan mengikuti `SATUSEHAT_ENV` (`.env` didahulukan) atau `'environment'` pada config. Nilai yang sah: `development`, `staging`, `production` — sama seperti modul lain.

## Logging

Pass any PSR-3 logger and every HTTP exchange is logged. Patient data is redacted first, so identifiers, names, birth dates, addresses, and contact details never reach your log file.

```php
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$logger = new Logger('satusehat');
$logger->pushHandler(new StreamHandler(__DIR__ . '/satusehat.log', Logger::DEBUG));

$ssclient = new SSClient(new Oauth2(), ['logger' => $logger]);
```

What gets logged:

| Level | When |
| --- | --- |
| `debug` | Every completed exchange: method, redacted URL, status, elapsed time. |
| `warning` | A retryable failure that will be retried, including the delay used. |
| `error` | The request failed permanently, or every retry was exhausted. |

Query strings and JSON bodies are passed through `PhiRedactor`, which works by **default-deny**: every value is replaced with `[REDACTED]` and only key names, plus a short list of structural fields (`resourceType`, `status`) and paging parameters (`_count`, `_offset`, ...), survive. Redacting a fixed list of "sensitive" keys is not enough for FHIR, because identity also lives in places whose key names look innocent — `subject.display`, `text.div`, and `note.text` all carried patient names and NIKs in earlier versions. Resource ids in the URL path (`/Patient/100000030009`) are masked for the same reason. Keep the logger level at `DEBUG` only on development machines — even redacted payloads reveal request volume and resource types.

## Token Caching

By default the access token lives in memory, per `Oauth2` instance. Under PHP-FPM that means one token request per worker, and each worker keeps paying that latency until its token expires.

`FileTokenStore` shares a single token between processes:

```php
use agumil\SatuSehatSDK\Auth\FileTokenStore;
use agumil\SatuSehatSDK\Auth\Oauth2;

$oauth2 = new Oauth2([
    'token_store' => new FileTokenStore(__DIR__ . '/storage/satusehat-token.json'),
]);
```

The file is written with `0600` permissions using an atomic temp-file-plus-rename, so a reader never sees a half-written token. The mode is then **verified** with `fileperms()`, because `chmod()` reports success even on filesystems that cannot apply POSIX permissions (FAT/vfat, some network shares); in that case writing fails loudly instead of leaving a readable credential behind. Refreshes run under an exclusive `flock`: when ten workers notice an expired token at the same moment, exactly one calls SATUSEHAT and the other nine pick up the result.

The stored token is signed with an HMAC keyed by your `client_secret`, which `Oauth2` injects automatically. A file that was written by someone else — for example a token belonging to a different organization — fails verification and is ignored, so clinical data cannot be steered to the wrong organization by dropping a file on disk. Files written by older versions (unsigned) are treated as absent and replaced on the next refresh. Note that two applications with different `client_secret` values cannot share one token file: each will reject the other's token and refresh on every call, which is the intended signal that they should not share it at all.

> **Windows note**
> Windows does not implement POSIX permission bits, so `chmod()` there only toggles the read-only flag and `fileperms()` always reports `0666`. The SDK does not pretend otherwise: mode checks are skipped on Windows, and confidentiality relies on the ACL of the directory you point at. On Windows, protect that directory with NTFS ACLs. The HMAC check still applies on every platform. Production PHP-FPM hosts are unaffected.

Point the path at a directory that is writable by the PHP user and outside your web root. The file contains a live credential — treat it like any other secret and never commit it.


## Testing

```bash
composer install
composer test
```

The suite runs on PHPUnit and needs no network access: Guzzle handlers are mocked, and KYC/FHIR transports are replaced with recorders. Two permission tests are skipped on Windows because the behaviour they assert (POSIX mode bits) does not exist there; `PosixFileTokenStore` in `tests/Support` covers the verification path on Windows instead.

CI runs four jobs on every push and pull request:

| Job | What it proves |
| --- | --- |
| `test` (PHP 8.0 – 8.4) | The suite passes on every version the library claims to support, and every file parses (`php -l`). |
| `lowest` | `composer update --prefer-lowest` still works — the version floors in `composer.json` are real, not aspirational. |
| `audit` | `composer audit` finds no known advisory in the locked dependencies. |

PHP 8.0 and 8.1 resolve their own PHPUnit (9.6 and 10.5) because the locked PHPUnit 11 needs PHP 8.2; PHP 8.0 runs the same tests through `composer test:compat`, which skips the config file that PHPUnit 9.6 cannot parse.

## Credits

This software uses the following open source packages:

- [Composer](https://getcomposer.org/)
- [Guzzle](https://github.com/guzzle/guzzle)
- [PSR-3 Log](https://github.com/php-fig/log)

## License

MIT License

Copyright (c) 2024 Agung Gumilang

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
