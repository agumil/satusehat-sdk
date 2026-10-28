<?php
namespace agumil\SatuSehatSDK\Interface\KFA;

use agumil\SatuSehatSDK\Auth\Oauth2;

interface ProductInterface
{
    public function __construct(Oauth2 $oauth2, array $config = []);
    public function getListProduct(array $params = []);
    public function getProduct(array $params = []);
    public function getListAlkesProduct(array $params = []);
    public function getListAlkesTemplate(array $params = []);
}
