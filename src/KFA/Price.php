<?php
namespace agumil\SatuSehatSDK\KFA;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Helper\StrHelper;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\Interface\KFA\PriceInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class Price extends Base implements PriceInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    /**
     * Harga JKN per produk (KFA v1).
     *
     * @param array $params `page`, `limit`, `kfa_code` wajib; `region_code`
     *                      (regional1..regional6) dan `document_ref` opsional
     */
    public function getListPriceJKN(array $params = [])
    {
        ValidatorHelper::required('page', $params['page'] ?? null);
        ValidatorHelper::unsignedInt('page', $params['page']);
        ValidatorHelper::required('limit', $params['limit'] ?? null);
        ValidatorHelper::positiveInt('limit', $params['limit']);
        ValidatorHelper::required('kfa_code', $params['kfa_code'] ?? null);

        $url = StrHelper::appendSlash($this->baseURL(1)) . 'farmalkes-price-jkn';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }
}
