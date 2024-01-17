<?php
namespace agumil\SatuSehatSDK\Terminology\KemKes;

class IdentifierSystem
{
    public static function organization(string $organizationId): string
    {
        return 'http://sys-ids.kemkes.go.id/organization/' . $organizationId;
    }

    public static function location(string $locationId): string
    {
        return 'http://sys-ids.kemkes.go.id/location/' . $locationId;
    }

    public static function patientNIK(): string
    {
        return 'https://fhir.kemkes.go.id/id/nik';
    }

    public static function patientNIKIbu(): string
    {
        return 'https://fhir.kemkes.go.id/id/nik-ibu';
    }

    public static function patientPaspor(): string
    {
        return 'https://fhir.kemkes.go.id/id/paspor';
    }

    public static function patientIHS(): string
    {
        return 'https://fhir.kemkes.go.id/id/ihs-number';
    }

    public static function encounter(string $organizationId): string
    {
        return 'http://sys-ids.kemkes.go.id/encounter/' . $organizationId;
    }

    public static function condition(string $organizationId): string
    {
        return 'http://sys-ids.kemkes.go.id/condition/' . $organizationId;
    }

    public static function allergy(string $organizationId): string
    {
        return 'http://sys-ids.kemkes.go.id/allergy/' . $organizationId;
    }
}
