<?php
namespace agumil\SatuSehatSDK;

class Endpoint
{
    const DEV_OAUTH2 = 'https://api-satusehat-dev.dto.kemkes.go.id/oauth2/v1';
    const DEV_FHIR = 'https://api-satusehat-dev.dto.kemkes.go.id/fhir-r4/v1';
    const DEV_CONSENT = 'https://api-satusehat-dev.dto.kemkes.go.id/consent/v1';

    const STG_OAUTH2 = 'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1';
    const STG_FHIR = 'https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1';
    const STG_CONSENT = 'https://api-satusehat-stg.dto.kemkes.go.id/consent/v1';

    const PROD_OAUTH2 = 'https://api-satusehat.kemkes.go.id/oauth2/v1';
    const PROD_FHIR = 'https://api-satusehat.kemkes.go.id/fhir-r4/v1';
    const PROD_CONSENT = 'https://api-satusehat.kemkes.go.id/consent/v1';
}
