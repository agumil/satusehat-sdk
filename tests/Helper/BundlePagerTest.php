<?php
namespace agumil\SatuSehatSDK\Tests\Helper;

use agumil\SatuSehatSDK\Helper\BundlePager;
use agumil\SatuSehatSDK\Response\Response;
use GuzzleHttp\Psr7\Response as PsrResponse;
use PHPUnit\Framework\TestCase;

class BundlePagerTest extends TestCase
{
    public function testPagerStopsWhenMaxPagesIsReached(): void
    {
        // Server selalu memberi halaman berikutnya, jadi hanya max_pages yang
        // bisa menghentikan loop. Sebelum diperbaiki, isFinished() tetap false
        // dan `while (! $pager->isFinished())` berputar selamanya.
        $page = 0;

        $pager = new BundlePager(function (array $params) use (&$page) {
            $page++;

            return $this->bundleWithNext('https://api.test/fhir-r4/v1/Patient?_page_token=' . $page);
        }, [], 3);

        $iterations = 0;

        while (! $pager->isFinished()) {
            $pager->next();
            $iterations++;

            if ($iterations > 20) {
                $this->fail('isFinished() tidak pernah bernilai true saat max_pages tercapai.');
            }
        }

        $this->assertSame(3, $iterations);
        $this->assertTrue($pager->isFinished());
        $this->assertNull($pager->next());
    }

    public function testPagerFinishesWhenThereIsNoNextLink(): void
    {
        $pager = new BundlePager(function (array $params) {
            return $this->bundleWithNext(null);
        });

        $response = $pager->next();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($pager->isFinished());
    }

    public function testPagerFinishesOnRepeatedNextLink(): void
    {
        $pager = new BundlePager(function (array $params) {
            return $this->bundleWithNext('https://api.test/fhir-r4/v1/Patient?_page_token=sama');
        }, ['_page_token' => 'sama']);

        $pager->next();

        $this->assertTrue($pager->isFinished());
    }

    private function bundleWithNext(?string $url): Response
    {
        $bundle = ['resourceType' => 'Bundle', 'entry' => []];

        if ($url !== null) {
            $bundle['link'] = [['relation' => 'next', 'url' => $url]];
        }

        return new Response(new PsrResponse(200, ['Content-Type' => 'application/json'], json_encode($bundle)));
    }
}
