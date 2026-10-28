<?php
namespace agumil\SatuSehatSDK\Auth;

/**
 * Store default: token hanya hidup selama instance Oauth2 hidup.
 *
 * Perilaku ini sama dengan SDK sebelum ada store, tetapi tetap melewati
 * antarmuka yang sama sehingga pemakaian TokenStoreInterface konsisten.
 */
class InMemoryTokenStore implements TokenStoreInterface
{
    private ?array $data = null;

    public function get(): ?array
    {
        return $this->data;
    }

    public function set(string $token, int $expiresAt): void
    {
        $this->data = [
            'token' => $token,
            'expires_at' => $expiresAt,
        ];
    }

    public function delete(): void
    {
        $this->data = null;
    }

    public function refresh(callable $fetch): array
    {
        $data = $fetch();
        $this->set($data['token'], $data['expires_at']);

        return $data;
    }
}
