<?php
namespace agumil\SatuSehatSDK\KFA;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Helper\StrHelper;
use agumil\SatuSehatSDK\Interface\KFA\PriceInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class Price extends Base implements PriceInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getListPriceJKN(array $params = [])
    {
        $url = StrHelper::appendSlash($this->baseURL(1)) . 'farmalkes-price-jkn';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }
}
