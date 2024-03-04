<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Exception\SSDataTypeException;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\Terminology\HL7\DoseAndRateType;
use agumil\SatuSehatSDK\Terminology\WHOCC\RouteAdministration;

class Dosage
{
    private ?int $sequence;

    private ?string $text;

    private CodeableConcept|string|int|null $additional_instruction;

    private ?string $patient_instruction;

    private ?Timing $timing;

    private CodeableConcept|string|null $route;

    private ?array $dose_and_rate;

    public function __construct(?int $sequence = null, ?string $text = null, CodeableConcept | string | int $additionalInstruction = null, ?string $patientInstruction = null, ?Timing $timing = null, CodeableConcept | string $route = null, ?string $doseType = null, Range | SimpleQuantity $dose = null, Ratio | Range | SimpleQuantity $doseRate = null)
    {
        $this->sequence = $sequence;
        $this->text = $text;

        if (isset($additionalInstruction)) {
            $this->additional_instruction = $this->parseAdditionalInstruction($additionalInstruction);
        }

        $this->patient_instruction = $patientInstruction;
        $this->timing = $timing;

        if (isset($route)) {
            $this->route = $this->parseRoute($route);
        }

        $this->route = $route;

        if (isset($doseType)) {
            ValidatorHelper::in('doseType', $doseType, DoseAndRateType::getCodes());

            $dtText = DoseAndRateType::getDisplayCode($doseType);
            $dtCoding = new Coding(DoseAndRateType::SYSTEM, $doseType, $dtText);
            $this->dose_and_rate['type'] = (new CodeableConcept($dtText, $dtCoding))->toArray();
        }

        if (isset($dose)) {
            if ($dose instanceof Range) {
                $this->dose_and_rate['doseRange'] = $dose->toArray();
            } elseif ($dose instanceof SimpleQuantity) {
                $this->dose_and_rate['doseQuantity'] = $dose->toArray();
            }
        }

        if (isset($doseRate)) {
            if ($doseRate instanceof Ratio) {
                $this->dose_and_rate['rateRatio'] = $doseRate->toArray();
            } elseif ($doseRate instanceof Range) {
                $this->dose_and_rate['rateRange'] = $doseRate->toArray();
            } elseif ($doseRate instanceof SimpleQuantity) {
                $this->dose_and_rate['rateQuantity'] = $doseRate->toArray();
            }
        }
    }

    private function parseAdditionalInstruction(CodeableConcept | string | int $val): CodeableConcept
    {
        if ($val instanceof CodeableConcept) {
            return $val;
        }

        $val = (string) $val;
        $dict = [
            '419111009' => 'Allow to dissolve under the tongue. Do not transfer from this container. Keep tightly closed. Discard eight weeks after opening',
            '418521000' => 'Avoid exposure of skin to direct sunlight or sun lamps',
            '419439004' => 'Caution flammable: keep away from fire or flames',
            '418194009' => 'Contains an aspirin-like medicine',
            '417980006' => 'Contains aspirin',
            '418850000' => 'Contains aspirin and paracetamol. Do not take with any other paracetamol products',
            '417995008' => 'Dissolve or mix with water before taking',
            '419529008' => 'Dissolved under the tongue',
            '419444006' => "Do not stop taking this medicine except on your doctor's advice",
            '418281004' => 'Do not take anything containing aspirin while taking this medicine',
            '420110001' => 'Do not take indigestion remedies at the same time of day as this medicine',
            '420082003' => 'Do not take indigestion remedies or medicines containing iron or zinc at the same time of day as this medicine',
            '419115000' => 'Do not take milk, indigestion remedies, or medicines containing iron or zinc at the same time of day as this medicine',
            '420003005' => 'Do not take more than . . . in 24 hours',
            '418443006' => 'Do not take more than . . . in 24 hours or . . . in any one week',
            '419437002' => 'Do not take more than 2 at any one time. Do not take more than 8 in 24 hours',
            '418637003' => 'Do not take with any other paracetamol products',
            '419473009' => 'Each',
            '421769005' => 'Follow directions',
            '311501008' => 'Half to one hour before food',
            '418991002' => 'Sucked or chewed',
            '418693002' => 'Swallowed whole, not chewed',
            '418577003' => 'Take at regular intervals. Complete the prescribed course unless otherwise directed',
            '717154004' => 'Take on an empty stomach',
            '421484000' => 'Then discontinue',
            '422327006' => 'Then stop',
            '419974005' => 'This medicine may color the urine',
            '417929005' => 'Times',
            '420162004' => 'To be spread thinly',
            '421984009' => 'Until finished',
            '420652005' => 'Until gone',
            '428579001' => 'Use with caution',
            '419822006' => 'Warning. Avoid alcoholic drink',
            '418071006' => 'Warning. Causes drowsiness which may continue the next day. If affected do not drive or operate machinery. Avoid alcoholic drink',
            '418849000' => 'Warning. Follow the printed instructions you have been given with this medicine',
            '418639000' => 'Warning. May cause drowsiness',
            '418954008' => 'Warning. May cause drowsiness. If affected do not drive or operate machinery',
            '418914006' => 'Warning. May cause drowsiness. If affected do not drive or operate machinery. Avoid alcoholic drink',
            '311504000' => 'With or after food',
            '419303009' => 'With plenty of water',
        ];

        if (is_numeric($val)) {
            if (!isset($dict[$val])) {
                throw new SSDataTypeException("SNOMEDCT | Additional dosage instructions, Code {$val} is not exist. SNOMEDCT International version 2022-12-31.");
            }

            $additionalInstruction = new CodeableConcept(null, new Coding('http://snomed.info/sct', $val, $dict[$val]));
        } else {
            $additionalInstruction = new CodeableConcept($val);
        }

        return $additionalInstruction;
    }

    private function parseRoute(CodeableConcept | string $val): CodeableConcept
    {
        if ($val instanceof CodeableConcept) {
            return $val;
        }

        ValidatorHelper::in('route', $val, RouteAdministration::getCodes());

        $system = RouteAdministration::SYSTEM;
        $display = RouteAdministration::getDisplayCode($val);

        return new CodeableConcept($display, new Coding($system, $val, $display));
    }

    public function toArray(): array
    {
        if (isset($this->sequence)) {
            $data['sequence'] = $this->sequence;
        }

        if (isset($this->text)) {
            $data['text'] = $this->text;
        }

        if (isset($this->additional_instruction)) {
            $data['additionalInstruction'] = $this->additional_instruction->toArray();
        }

        if (isset($this->patient_instruction)) {
            $data['patientInstruction'] = $this->patient_instruction;
        }

        if (isset($this->timing)) {
            $data['timing'] = $this->timing->toArray();
        }

        if (isset($this->route)) {
            $data['route'] = $this->route->toArray();
        }

        if (isset($this->dose_and_rate)) {
            $data['doseAndRate'] = $this->dose_and_rate;
        }

        return $data;
    }
}
