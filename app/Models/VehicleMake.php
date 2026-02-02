<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleMake extends Model
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
        'year',
        'name',
    ];
}
