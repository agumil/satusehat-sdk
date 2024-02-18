<?php
namespace agumil\SatuSehatSDK;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\KFA\Price;
use agumil\SatuSehatSDK\KFA\Product;

class SSClientKFA
{
    private Oauth2 $oauth2;

    private array $config;

    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        $this->oauth2 = $oauth2;
        $this->config = $config;
    }

    public function getListATC(array $params = [])
    {
        return (new Product($this->oauth2, $this->config))->getListATC($params);
    }

    public function getListProductByATC(array $params = [])
    {
        return (new Product($this->oauth2, $this->config))->getListProductByATC($params);
    }

    public function getListTag(array $params = [])
    {
        return (new Product($this->oauth2, $this->config))->getListTag($params);
    }

    public function getListProductByTag(array $params = [])
    {
        return (new Product($this->oauth2, $this->config))->getListProductByTag($params);
    }

    public function getListProduct(array $params = [])
    {
        return (new Product($this->oauth2, $this->config))->getListProduct($params);
    }

    public function getProduct(array $params = [])
    {
        return (new Product($this->oauth2, $this->config))->getProduct($params);
    }

    public function getListPriceJKN(array $params = [])
    {
        return (new Price($this->oauth2, $this->config))->getListPriceJKN($params);
    }
}
