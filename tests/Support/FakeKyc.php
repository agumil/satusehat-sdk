<?php
namespace agumil\SatuSehatSDK\Tests\Support;

use agumil\SatuSehatSDK\KYC\Kyc;
use agumil\SatuSehatSDK\Request\HttpRequest;
use LogicException;

/**
 * `Kyc` dengan transport tiruan, supaya header yang benar-benar dikirim bisa
 * diperiksa tanpa memanggil endpoint KYC.
 *
 * Transport dititipkan lewat config, bukan parameter konstruktor, karena
 * `KycInterface` mengunci signature `__construct(Oauth2 $oauth2, array $config = [])`.
 */
class FakeKyc extends Kyc
{
    const HTTP_KEY = '_fake_http';

    protected function httpRequest(): HttpRequest
    {
        $http = $this->config[self::HTTP_KEY] ?? null;

        if (!($http instanceof FakeHttpRequest)) {
            throw new LogicException('FakeKyc: config[' . self::HTTP_KEY . '] harus berisi FakeHttpRequest.');
        }

        return $http;
    }
}
