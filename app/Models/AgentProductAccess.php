<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentProductAccess extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'agent_id',
        'insurance_product_code',
        'is_active',
        'priority',
    ];
}
