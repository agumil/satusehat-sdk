<?php
namespace agumil\SatuSehatSDK\Tests\Helper;

use agumil\SatuSehatSDK\Helper\PhiRedactor;
use PHPUnit\Framework\TestCase;

/**
 * Regresi untuk kebocoran PHI di log.
 *
 * Versi lama memakai daftar hitam nama kunci (`name`, `identifier`,
 * `birthdate`, ...). Semua kasus di bawah lolos utuh dengan pendekatan itu,
 * karena FHIR menyimpan identitas pada kunci yang namanya tidak mengandung
 * kata sensitif.
 */
class PhiRedactorTest extends TestCase
{
    public function testIdentityFieldsThatUsedToLeakAreMasked(): void
    {
        $payload = [
            'resourceType' => 'Patient',
            'active' => true,
            'identifier' => [
                ['system' => 'https://fhir.kemkes.go.id/id/nik', 'value' => '3171022809990001'],
            ],
            'name' => [['text' => 'Budi Santoso']],
            // Tiga tempat yang dulu bocor:
            'subject' => ['reference' => 'Patient/100000030009', 'display' => 'Budi Santoso'],
            'text' => ['status' => 'generated', 'div' => '<div>Pasien Budi Santoso, NIK 3171022809990001, DBD</div>'],
            'note' => [['text' => 'Pasien mengaku demam tiga hari']],
        ];

        $redacted = PhiRedactor::redact($payload);
        $encoded = json_encode($redacted);

        $this->assertStringNotContainsString('Budi', $encoded);
        $this->assertStringNotContainsString('3171022809990001', $encoded);
        $this->assertStringNotContainsString('demam', $encoded);
        $this->assertStringNotContainsString('100000030009', $encoded);
    }

    public function testStructuralKeysAreKeptSoLogsStayUseful(): void
    {
        $redacted = PhiRedactor::redact([
            'resourceType' => 'Observation',
            'status' => 'final',
            'valueQuantity' => ['value' => 38.5],
        ]);

        $this->assertSame('Observation', $redacted['resourceType']);
        $this->assertSame('final', $redacted['status']);
        $this->assertSame(PhiRedactor::MASK, $redacted['valueQuantity']['value']);
    }

    public function testShapeOfPayloadIsPreserved(): void
    {
        $redacted = PhiRedactor::redact([
            'resourceType' => 'Patient',
            'subject' => ['reference' => 'Patient/1', 'display' => 'Budi'],
            'note' => [['text' => 'a'], ['text' => 'b']],
        ]);

        // Nama kunci dan jumlah elemen tetap ada; hanya nilainya hilang.
        $this->assertSame(['reference', 'display'], array_keys($redacted['subject']));
        $this->assertCount(2, $redacted['note']);
    }

    public function testScalarAndObjectPayloadsAreMasked(): void
    {
        $this->assertSame(PhiRedactor::MASK, PhiRedactor::redact('Budi Santoso'));

        $object = (object) ['display' => 'Budi Santoso'];
        $this->assertSame(PhiRedactor::MASK, PhiRedactor::redact($object)['display']);
    }

    public function testSearchQueryValuesAreMasked(): void
    {
        $url = 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/Patient'
            . '?identifier=https%3A%2F%2Ffhir.kemkes.go.id%2Fid%2Fnik%7C9271060312000001'
            . '&name=Budi&birthdate=1990-01-01';

        $redacted = PhiRedactor::redactUrl($url);

        $this->assertStringNotContainsString('9271060312000001', $redacted);
        $this->assertStringNotContainsString('Budi', $redacted);
        $this->assertStringNotContainsString('1990-01-01', $redacted);
    }

    public function testPagingParametersStayReadable(): void
    {
        $redacted = PhiRedactor::redactUrl('https://api-satusehat.kemkes.go.id/fhir-r4/v1/Patient?_count=100&_offset=200');

        $this->assertStringContainsString('_count=100', $redacted);
        $this->assertStringContainsString('_offset=200', $redacted);
    }

    public function testResourceIdInPathIsMasked(): void
    {
        $this->assertSame(
            'https://api-satusehat.kemkes.go.id/fhir-r4/v1/Patient/[REDACTED]',
            PhiRedactor::redactUrl('https://api-satusehat.kemkes.go.id/fhir-r4/v1/Patient/100000030009')
        );
    }

    public function testOperationNameIsNotMistakenForAnId(): void
    {
        $this->assertSame(
            '/fhir-r4/v1/Patient/[REDACTED]/$everything',
            PhiRedactor::redactUrl('/fhir-r4/v1/Patient/100000030009/$everything')
        );
    }

    public function testUrlWithoutQueryIsReturnedUnchangedWhenThereIsNoId(): void
    {
        $this->assertSame(
            'https://api-satusehat.kemkes.go.id/fhir-r4/v1/Patient',
            PhiRedactor::redactUrl('https://api-satusehat.kemkes.go.id/fhir-r4/v1/Patient')
        );
    }
}
