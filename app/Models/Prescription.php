<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'prescription_number',
        'doctor_id',
        'patient_id',
        'diagnosis',
        'notes',
        'status',
        'issued_at',
        'expires_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Relationships
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    // Generate unique prescription number
    public static function generateNumber(): string
    {
        $year = date('Y');
        $last = self::where('prescription_number', 'LIKE', "RX-{$year}-%")
            ->orderBy('prescription_number', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->prescription_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('RX-%s-%04d', $year, $nextNumber);
    }
}