<?php require __DIR__ . '/../bootstrap.php';

use agumil\SatuSehatSDK\Auth\Oauth2;

$config['environment'] = 'staging';
$config['organization_id'] = $parentOrganizationId = 'g01Ema3YViimrhTY06o9y8fFO5jp4uhQ3ISdG5UA65hDCDtiqaQpFgDQGLjVfpmG';
$config['client_id'] = 'nNRB27AyG00wLILTgIeuaoxYLHBOrIakiGcCEIgMUQ18HO1V';
$config['client_secret'] = 'g01Ema3YViimrhTY06o9y8fFO5jp4uhQ3ISdG5UA65hDCDtiqaQpFgDQGLjVfpmG';

$oauth2 = new Oauth2($config);
