<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyInventory extends Model
{
    use HasFactory;

    protected $table = 'pharmacy_inventory';

    protected $fillable = [
        'pharmacy_id',
        'medicine_id',
        'stock_quantity',
        'selling_price',
        'reorder_level',
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}