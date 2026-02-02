<?php

namespace App\Models;

use App\Shared\Domain\Enums\GenericStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    public function scopeCode(Builder $query, string $code)
    {
        return $query->where('code', $code);
    }

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
