<?php
namespace agumil\SatuSehatSDK\Auth;

/**
 * Penyimpanan access token yang bisa dipakai lintas instance/proses.
 *
 * Tanpa store, setiap proses PHP (mis. tiap request PHP-FPM) meminta token baru
 * ke SATUSEHAT. Selain menambah latensi, pola itu mendekatkan aplikasi ke rate
 * limit endpoint token.
 */
interface TokenStoreInterface
{
    /**
     * @return array{token: string, expires_at: int}|null null bila belum ada token
     */
    public function get(): ?array;

    public function set(string $token, int $expiresAt): void;

    public function delete(): void;

    /**
     * Mengambil token valid dari store, atau memanggil $fetch() sekali saja.
     *
     * Implementasi lintas proses wajib mengunci store selama $fetch() berjalan
     * (single-flight), supaya N proses yang menemukan token kedaluwarsa pada
     * saat bersamaan tidak menghasilkan N request token.
     *
     * @param callable(): array{token: string, expires_at: int} $fetch
     *
     * @return array{token: string, expires_at: int}
     */
    public function refresh(callable $fetch): array;
}
