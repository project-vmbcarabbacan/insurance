<?php

namespace App\Models;

use App\Shared\Domain\Enums\DocumentModule;
use Illuminate\Database\Eloquent\Model;

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
