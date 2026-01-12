<?php

namespace App\Models;

use App\Shared\Domain\Enums\Currency;
use App\Shared\Domain\Enums\QuotationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Quotation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'quote_number',
        'lead_id',
        'customer_id',
        'insurance_product_code',
        'price',
        'vat',
        'currency',
        'status',
        'valid_until',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => QuotationStatus::class,
            'currency' => Currency::class,
        ];
    }

    /**
     * Get all AuditLog attached to this quotation.
     */
    public function audits(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    /**
     * Get the provider that is assigned to this quoation.
     */
    public function provider()
    {
        return $this->belongsTo(PolicyProvider::class);
    }

    /**
     * Get the plan that is assigned to this quoation.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
