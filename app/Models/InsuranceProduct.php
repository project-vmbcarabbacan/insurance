<?php

namespace App\Models;

use App\Shared\Domain\Enums\GenericStatus;
use Illuminate\Database\Eloquent\Model;

class InsuranceProduct extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * Get all plans that are assigned to this insurance product.
     *
     * This defines a one-to-many relationship where
     * a single product can be associated with multiple plans.
     */
    public function plans()
    {
        return $this->hasMany(Plan::class)->where('status', GenericStatus::ACTIVE);
    }
}
