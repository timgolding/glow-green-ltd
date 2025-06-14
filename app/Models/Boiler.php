<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Boiler extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name',
        'manufacturer_part_number',
        'boiler_manufacturer_id',
        'fuel_type_id',
        'description',
        'sku',
        'url',
    ];

    public function manufacturer()
    {
        return $this->belongsTo(BoilerManufacturer::class, 'boiler_manufacturer_id');
    }

    public function fuelType()
    {
        return $this->belongsTo(BoilerFuelType::class);
    }

}
