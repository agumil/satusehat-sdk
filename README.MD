
<h4 align="center">Unofficial API wrapper package to integrate with SATUSEHAT platform.</h4>

<p align="center">
  <a href="#note">Note</a> •
  <a href="#key-features">Key Features</a> •
  <a href="#installation">Installation</a> •
  <a href="#quick-start">Quick Start</a> •
  <a href="#license">License</a>
</p>

![screenshot](https://d290ny10omyv12.cloudfront.net/images/satusehat-og-fb.png)

## Note

This package is on-progress, documentation is very limited AND you may find out that several components are incomplete. Please try this on your Development Environment first to prevent any possible break to your project.

## Key Features

* Payload Builder
  - Build your payload as consistent as possible with payload builder
  - Don't like it ? You could ignore this as well
* Keep it simple
  - Less 3rd party library, only required the necessary one
  - Resource class that fulfill HL7 FHIR standard
* Flexible
  - Not constrained with specific framework
  - Send your payload however you like to match your project
* Support php >= 7.4

## Installation

To run this library, you'll need PHP 7.4+, and Composer. From your command line:

```bash
# install this package
$ composer require agumil/satusehat-sdk
```

You could save your SATUSEHAT ENVIRONMENT, CLIENT ID, and CLIENT_SECRET to your .env file(s). From your command line:
```bash
# install recommended library
$ composer require vlucas/phpdotenv
```

Supported ENV:
```bash
# Choose one of the three options below
SATUSEHAT_ENV=development|staging|production
SATUSEHAT_ORGANIZATION_ID=organization_id
SATUSEHAT_CLIENT_ID=client_id
SATUSEHAT_CLIENT_SECRET=client_secret
```

> **Note**
> If you're using PHP verson 7.3 or below, please update your PHP.

## Quick Start
With PHPDOTENV
```php
use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\SSClient;

$ssclient = new SSClient(new Oauth2());
$response = $ssclient->getPractitioner([
    'identifier' => 'https://fhir.kemkes.go.id/id/nakes-his-number|10009880728',
]);
```

Without PHPDOTENV
```php
use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Endpoint;
use agumil\SatuSehatSDK\SSClient;

$config1['base_url'] = Endpoint::DEV_OAUTH2;
$config1['client_id'] = 'your_client_id';
$config1['client_secret'] = 'your_client_secret';

$config2['base_url'] = Endpoint::DEV_FHIR;

$ssclient = new SSClient(new Oauth2($config1), $config2);
$response = $ssclient->getPractitioner([
    'identifier' => 'https://fhir.kemkes.go.id/id/nakes-his-number|10009880728',
]);
```

## Credits

This software uses the following open source packages:

- [Composer](https://getcomposer.org/)
- [Guzzle](https://github.com/guzzle/guzzle)

## License

MIT License

Copyright (c) 2024 Agung Gumilang

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
