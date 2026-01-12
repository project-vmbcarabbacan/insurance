<?php

namespace App\Models;

use App\Shared\Domain\Enums\CustomerStatus;
use App\Shared\Domain\Enums\GenderType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Builder;

class Customer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'status',
        'first_name',
        'last_name',
        'dob',
        'gender',
        'phone_country_code',
        'phone_number',
        'email',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => CustomerStatus::class,
            'gender' => GenderType::class
        ];
    }

    /**
     * Scope a query to filter users by a search term across multiple fields.
     *
     * This scope searches for the given term in:
     * - first_name
     * - last_name
     * - email
     * - phone_number
     * - full name (first_name + ' ' + last_name)
     * - full phone number (phone_country_code + phone_number)
     *
     * It uses SQL LIKE with wildcards to match partial terms.
     *
     * Example usage:
     * ```php
     * User::search('john')->get();
     * User::search('john doe')->get();
     * User::search('+1415')->get();
     * ```
     *
     * @param Builder $query The Eloquent query builder
     * @param string $term The search term to match
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $term = trim($term);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $like = "%{$term}%";

            $q->where('first_name', 'like', $like)
                ->orWhere('last_name', 'like', $like)
                ->orWhere('email', 'like', $like)
                ->orWhere('phone_number', 'like', $like)

                // Search by full name (first_name + last_name)
                ->orWhereRaw(
                    "CONCAT(first_name, ' ', last_name) LIKE ?",
                    [$like]
                )

                // Search by full phone number (phone_country_code + phone_number)
                ->orWhereRaw(
                    "CONCAT(phone_country_code, phone_number) LIKE ?",
                    [$like]
                );
        });
    }

    /**
     * Scope to filter by email
     */
    public function scopeEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Scope to filter by phone and country code
     *
     * @param string $phoneNumber
     * @param string $phoneCountryCode defaulted to +971
     */
    public function scopePhone(Builder $query, string $phoneNumber, string $phoneCountryCode = '+971'): Builder
    {
        return $query->where(['phone_number' => $phoneNumber, 'phone_country_code' => $phoneCountryCode]);
    }


    /**
     * Get the user that is assigned to this customer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all documents attached to this customer.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'owner');
    }

    /**
     * Get all AuditLog attached to this customer.
     */
    public function audits(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}
