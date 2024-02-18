<?php
namespace agumil\SatuSehatSDK\Interface\KFA;

use agumil\SatuSehatSDK\Auth\Oauth2;

interface PriceInterface
{
    public function __construct(Oauth2 $oauth2, array $config = []);
    public function getListPriceJKN(array $params = []);
}
