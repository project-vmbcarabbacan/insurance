<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleTrim extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'reference_id ',
        'vehicle_make_id',
        'vehicle_model_id',
        'year',
        'name',
        'description',
        'msrp',
        'type',
        'seats',
        'doors',
        'engine_type',
        'fuel_type',
        'cylinders',
    ];
}
