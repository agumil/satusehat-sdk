

<h4 align="center">Paquete no oficial de wrapper de API para integrarse con la plataforma SATUSEHAT.</h4>

<p align="center">
  <a href="#note">Nota</a> •
  <a href="#key-features">Características Principales</a> •
  <a href="#installation">Instalación</a> •
  <a href="#quick-start">Inicio Rápido</a> •
  <a href="#license">Licencia</a>
</p>

![screenshot](https://d290ny10omyv12.cloudfront.net/images/satusehat-og-fb.png)

## Nota

Este paquete está en desarrollo, la documentación es muy limitada y es posible que varios componentes estén incompletos. Por favor, pruébalo primero en tu Entorno de Desarrollo para evitar cualquier posible interrupción en tu proyecto.

## Características Principales

* Constructor de Cargas (Payload Builder)
  - Construye tu carga de datos lo más consistente posible con el constructor de payloads
  - ¿No te gusta? También puedes ignorar esta función
* Mantenerlo simple
  - Menor uso de bibliotecas de terceros, solo se requieren las necesarias
  - Clase de recursos que cumple con el estándar HL7 FHIR
* Flexible
  - No está restringido a un framework específico
  - Envía tu carga de datos como prefieras para adaptarla a tu proyecto
* Compatible con PHP >= 7.4

## Instalación

Para ejecutar esta biblioteca, necesitarás PHP 7.4+ y Composer. Desde tu línea de comandos:

```bash
# instalar este paquete
$ composer require agumil/satusehat-sdk
```

Puedes guardar tu ENTORNO SATUSEHAT, CLIENTE ID y CLIENTE_SECRET en tu(s) archivo(s) .env. Desde tu línea de comandos:
```bash
# instalar la biblioteca recomendada
$ composer require vlucas/phpdotenv
```

Variables de entorno soportadas:
```bash
# Elige una de las dos opciones a continuación
SATUSEHAT_ENV=staging|production
SATUSEHAT_ORGANIZATION_ID=organization_id
SATUSEHAT_CLIENT_ID=client_id
SATUSEHAT_CLIENT_SECRET=client_secret
```

> **Nota**
> Si estás usando PHP versión 7.3 o inferior, por favor actualiza tu versión de PHP.

## Inicio Rápido
Con PHPDOTENV
```php
use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\SSClient;

$ssclient = new SSClient(new Oauth2());
$response = $ssclient->getPractitioner([
    'identifier' => 'https://fhir.kemkes.go.id/id/nakes-his-number|10009880728',
]);
```

Sin PHPDOTENV
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

## Créditos

Este software utiliza los siguientes paquetes de código abierto:

- [Composer](https://getcomposer.org/)
- [Guzzle](https://github.com/guzzle/guzzle)

## Licencia

Licencia MIT

Derechos de autor (c) 2024 Agung Gumilang

Por la presente se concede permiso, libre de cargos, a cualquier persona que obtenga una copia
de este software y los archivos de documentación asociados (el "Software"), para utilizar
el Software sin restricción, incluyendo sin limitación los derechos
para usar, copiar, modificar, fusionar, publicar, distribuir, sublicenciar y/vender
copias del Software, y para permitir a las personas a las que se les proporcione el Software
a hacer lo mismo, sujeto a las siguientes condiciones:

El aviso de derechos de autor anterior y este aviso de permiso se incluirán en
todas las copias o porciones sustanciales del Software.

EL SOFTWARE SE PROPORCIONA "TAL CUAL", SIN GARANTÍA DE NINGÚN TIPO, EXPRESA O
IMPLÍCITA, INCLUYENDO PERO NO LIMITADO A LAS GARANTÍAS DE COMERCIABILIDAD,
APTITUD PARA UN PROPÓSITO EN PARTICULAR Y NO INFRACCIÓN. EN NINGÚN CASO LOS
AUTORES O TITULARES DE LOS DERECHOS DE AUTOR SERÁN RESPONSABLES DE NINGUNA RECLAMACIÓN, DAÑOS U OTRAS
RESPONSABILIDADES, SEA EN UNA ACCIÓN DE CONTRATO, ILÍCITO O DE OTRO TIPO, QUE SURJA DE,
FUERA DE O EN CONEXIÓN CON EL SOFTWARE O EL USO U OTROS ASUNTOS EN
EL SOFTWARE.
