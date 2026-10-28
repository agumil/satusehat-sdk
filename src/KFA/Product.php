<?php
namespace agumil\SatuSehatSDK\KFA;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Helper\StrHelper;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\Interface\KFA\ProductInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class Product extends Base implements ProductInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    /**
     * Daftar produk KFA v2 (berhalaman).
     *
     * @param array $params `page`, `size`, `product_type` wajib; `from_date`,
     *                      `to_date`, `farmalkes_type`, `keyword`,
     *                      `template_code`, `packaging_code` opsional
     */
    public function getListProduct(array $params = [])
    {
        ValidatorHelper::required('page', $params['page'] ?? null);
        ValidatorHelper::unsignedInt('page', $params['page']);
        ValidatorHelper::required('size', $params['size'] ?? null);
        ValidatorHelper::positiveInt('size', $params['size']);
        ValidatorHelper::required('product_type', $params['product_type'] ?? null);

        $url = StrHelper::appendSlash($this->baseURL(2)) . 'products/all';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    /**
     * Detail satu produk KFA v2.
     *
     * @param array $params `identifier` (kfa|nie|lkpp) dan `code` wajib
     */
    public function getProduct(array $params = [])
    {
        ValidatorHelper::required('identifier', $params['identifier'] ?? null);
        ValidatorHelper::in('identifier', $params['identifier'], ['kfa', 'nie', 'lkpp']);
        ValidatorHelper::required('code', $params['code'] ?? null);

        $url = StrHelper::appendSlash($this->baseURL(2)) . 'products';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    /**
     * Daftar varian produk ALKES (KFA v3).
     *
     * Body dikirim apa adanya: dokumentasi menandai seluruh field sebagai wajib,
     * padahal pemakaian nyatanya memakai sebagian saja, sehingga validasi ketat
     * di SDK justru menghalangi.
     */
    public function getListAlkesProduct(array $params = [])
    {
        $url = StrHelper::appendSlash($this->baseURL(3)) . 'alkes/products';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->post($url, $params));
    }

    /**
     * Daftar template produk ALKES (KFA v3). Sama seperti varian, tanpa
     * `reference_code`.
     */
    public function getListAlkesTemplate(array $params = [])
    {
        $url = StrHelper::appendSlash($this->baseURL(3)) . 'alkes/template';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->post($url, $params));
    }
}
