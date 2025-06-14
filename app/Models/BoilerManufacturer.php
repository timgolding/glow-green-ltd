<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoilerManufacturer extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['name'];

    public function boilers()
    {
        return $this->hasMany(Boiler::class);
    }
}
