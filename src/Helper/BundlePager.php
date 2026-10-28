<?php
namespace agumil\SatuSehatSDK\Helper;

use agumil\SatuSehatSDK\Response\Response;

/**
 * Penelusur halaman Bundle FHIR.
 *
 * SATUSEHAT mengembalikan `link.next` berisi token halaman opaque
 * (`_page_token`), jadi halaman berikutnya hanya bisa diambil dengan
 * mengirim ulang parameter dari link tersebut ke endpoint yang sama. Pager ini
 * melakukan itu tanpa perlu tahu URL resource-nya:
 *
 * ```php
 * $pager = new BundlePager(
 *     fn (array $params) => $client->getPatient($params),
 *     ['identifier' => 'https://fhir.kemkes.go.id/id/nik|9271060312000001', '_count' => 100]
 * );
 *
 * while (($response = $pager->next()) !== null) {
 *     foreach (BundleHelper::entries($response->getContentAsArray()) as $entry) {
 *         // ...
 *     }
 * }
 * ```
 */
class BundlePager
{
    /** @var callable(array): Response */
    private $fetch;

    private array $params;

    private int $max_pages;

    private int $page = 0;

    private bool $finished = false;

    public function __construct(callable $fetch, array $params = [], int $max_pages = 1000)
    {
        $this->fetch = $fetch;
        $this->params = $params;
        $this->max_pages = $max_pages;
    }

    /**
     * Mengambil halaman berikutnya, atau null bila sudah habis.
     */
    public function next(): ?Response
    {
        if ($this->finished || $this->page >= $this->max_pages) {
            return null;
        }

        $params = $this->params;
        $response = ($this->fetch)($params);

        $this->page++;

        if ($response->getHttpCode() < 200 || $response->getHttpCode() >= 300) {
            $this->finished = true;

            return $response;
        }

        $next_params = BundleHelper::nextParams((array) $response->getContentAsArray());

        // Berhenti bila tidak ada halaman berikutnya, atau bila server
        // mengembalikan link yang sama (mencegah loop tanpa akhir).
        if ($next_params === null || $next_params === $params) {
            $this->finished = true;
        } else {
            $this->params = $next_params;
        }

        return $response;
    }

    public function page(): int
    {
        return $this->page;
    }

    public function isFinished(): bool
    {
        // Batas halaman juga berarti selesai. Tanpa ini, pemakaian
        // `while (! $pager->isFinished()) { $pager->next(); }` berputar
        // selamanya: next() mengembalikan null setelah max_pages tetapi flag
        // finished tidak pernah dinyalakan.
        return $this->finished || $this->page >= $this->max_pages;
    }
}
