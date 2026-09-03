<?php

namespace App\Services;

use App\Models\Prescription;

class PrescriptionQRService
{
    protected $secret;

    public function __construct()
    {
        $this->secret = config('app.qr_secret', 'your-secret-key-here');
    }

    public function generatePayload(Prescription $prescription): string
    {
        $payload = [
            'v' => 1,
            'rx' => $prescription->prescription_number,
            'ts' => now()->timestamp,
        ];

        $payload['sig'] = $this->sign($payload);

        return json_encode($payload);
    }

    public function validate(string $qrContent): array
    {
        $payload = json_decode($qrContent, true);

        if (!$payload || !isset($payload['v']) || !isset($payload['rx']) || !isset($payload['sig'])) {
            throw new \Exception('Invalid QR code format.');
        }

        $sig = $payload['sig'];
        unset($payload['sig']);

        $expectedSig = $this->sign($payload);

        if (!hash_equals($expectedSig, $sig)) {
            throw new \Exception('Invalid QR code signature.');
        }

        return $payload;
    }

    protected function sign(array $payload): string
    {
        $data = $payload['rx'] . '|' . ($payload['ts'] ?? '');
        return hash_hmac('sha256', $data, $this->secret);
    }
}