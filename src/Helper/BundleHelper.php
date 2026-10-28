<?php
namespace agumil\SatuSehatSDK\Helper;

/**
 * Pembantu membaca Bundle FHIR (hasil pencarian) tanpa harus menghafal
 * strukturnya. Semua method menerima array hasil `Response::getContentAsArray()`.
 */
class BundleHelper
{
    public static function isBundle($content): bool
    {
        return is_array($content) && ($content['resourceType'] ?? null) === 'Bundle';
    }

    /**
     * @return array Daftar entry; kosong bila Bundle tidak punya hasil
     */
    public static function entries(array $bundle): array
    {
        return isset($bundle['entry']) && is_array($bundle['entry']) ? $bundle['entry'] : [];
    }

    public static function total(array $bundle): ?int
    {
        return isset($bundle['total']) ? (int) $bundle['total'] : null;
    }

    public static function linkUrl(array $bundle, string $relation): ?string
    {
        foreach ($bundle['link'] ?? [] as $link) {
            if (is_array($link) && ($link['relation'] ?? null) === $relation && isset($link['url'])) {
                return (string) $link['url'];
            }
        }

        return null;
    }

    public static function nextUrl(array $bundle): ?string
    {
        return self::linkUrl($bundle, 'next');
    }

    /**
     * Query string dari `link.next` dalam bentuk parameter.
     *
     * SATUSEHAT memakai token halaman opaque (`_page_token`), jadi parameter
     * inilah yang harus dikirim ulang ke endpoint yang sama untuk halaman
     * berikutnya — URL lengkapnya tidak perlu dirakit sendiri.
     */
    public static function nextParams(array $bundle): ?array
    {
        $url = self::nextUrl($bundle);

        if ($url === null) {
            return null;
        }

        $query = parse_url($url, PHP_URL_QUERY);

        if (!is_string($query) || $query === '') {
            return null;
        }

        parse_str($query, $params);

        return $params === [] ? null : $params;
    }
}
