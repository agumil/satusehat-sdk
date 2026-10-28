<?php
namespace agumil\SatuSehatSDK\Tests\Support;

use Psr\Log\AbstractLogger;

/**
 * Logger PSR-3 yang menyimpan semua record di memori, supaya isi log bisa
 * diperiksa tanpa menulis file.
 *
 * Parameter `$message` sengaja tidak diberi tipe agar kompatibel dengan
 * psr/log 1.x, 2.x, dan 3.x.
 */
class MemoryLogger extends AbstractLogger
{
    /** @var array<int, array{level: mixed, message: string, context: array}> */
    public array $records = [];

    public function log($level, $message, array $context = []): void
    {
        $this->records[] = [
            'level' => $level,
            'message' => (string) $message,
            'context' => $context,
        ];
    }

    public function dump(): string
    {
        return json_encode($this->records, JSON_UNESCAPED_SLASHES) ?: '';
    }
}
