<?php

namespace App\Models;

use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Shared\Domain\Enums\DocumentModule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class DocumentType extends Model
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
        'module',
        'name',
        'description',
        'required',
    ];

    protected $casts = [
        'required' => 'boolean'
    ];

    public function scopeProduct(Builder $query, LeadProductType $product)
    {
        return $query->where('module', $product->value);
    }

    public function scopeProductGeneral(Builder $query, LeadProductType $product)
    {
        return $query->whereIn('module', ['general', $product->value]);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'module' => DocumentModule::class,
        ];
    }
}
