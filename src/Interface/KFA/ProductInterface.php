<?php
namespace agumil\SatuSehatSDK\Interface\KFA;

use agumil\SatuSehatSDK\Auth\Oauth2;

interface ProductInterface
{
    public function __construct(Oauth2 $oauth2, array $config = []);
    public function getListATC(array $params = []);
    public function getListTag(array $params = []);
    public function getListProduct(array $params = []);
    public function getListProductByTag(array $params = []);
    public function getListProductByATC(array $params = []);
    public function getProduct(array $params = []);
}
