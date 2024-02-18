<?php
namespace agumil\SatuSehatSDK\KFA;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Helper\StrHelper;
use agumil\SatuSehatSDK\Interface\KFA\ProductInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class Product extends Base implements ProductInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getListATC(array $params = [])
    {
        $url = StrHelper::appendSlash($this->baseURL(1)) . 'atc';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    public function getListProductByATC(array $params = [])
    {
        $url = StrHelper::appendSlash($this->baseURL(1)) . 'products/atc';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    public function getListTag(array $params = [])
    {
        $url = StrHelper::appendSlash($this->baseURL(1)) . 'tags';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    public function getListProductByTag(array $params = [])
    {
        $url = StrHelper::appendSlash($this->baseURL(1)) . 'products/tag';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    public function getListProduct(array $params = [])
    {
        $url = StrHelper::appendSlash($this->baseURL(2)) . 'products/all';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    public function getProduct(array $params = [])
    {
        $url = StrHelper::appendSlash($this->baseURL(2)) . 'products';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }
}
