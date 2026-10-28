<?php
namespace agumil\SatuSehatSDK\Tests\Support;

use agumil\SatuSehatSDK\Auth\FileTokenStore;

/**
 * `FileTokenStore` yang menganggap platformnya POSIX.
 *
 * Dipakai di Windows untuk menguji jalur verifikasi permission: di sana
 * `chmod()` selalu melaporkan berhasil tanpa mengubah mode, yaitu kondisi yang
 * justru harus ditolak oleh `restrictPermission()`.
 */
class PosixFileTokenStore extends FileTokenStore
{
    protected function isPosixFilesystem(): bool
    {
        return true;
    }
}
