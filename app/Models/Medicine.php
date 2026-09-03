<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'generic_id',
        'name',
        'strength',
        'dosage_form',
        'sku',
        'barcode',
        'description',
        'side_effects',
        'contraindications',
        'requires_prescription',
        'is_active',
    ];

    protected $casts = [
        'requires_prescription' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(MedicineCategory::class, 'category_id');
    }

    public function generic()
    {
        return $this->belongsTo(MedicineGeneric::class, 'generic_id');
    }

    public function pharmacyInventory()
    {
        return $this->hasMany(PharmacyInventory::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'ILIKE', "%{$search}%")
              ->orWhere('strength', 'ILIKE', "%{$search}%")
              ->orWhere('sku', 'ILIKE', "%{$search}%")
              ->orWhere('barcode', 'ILIKE', "%{$search}%")
              ->orWhereHas('generic', function ($g) use ($search) {
                  $g->where('name', 'ILIKE', "%{$search}%");
              })
              ->orWhereHas('category', function ($c) use ($search) {
                  $c->where('name', 'ILIKE', "%{$search}%");
              });
        });
    }
}