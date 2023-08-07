<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    use HasFactory;
    public $table = 'user_tokens';

    public $fillable = [
        'user_id',
        'token',
        'token_type',
        'scopes',
        'is_revoked',
        'expires_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'token' => 'string',
        'token_type' => 'string',
        'scopes' => 'string',
        'is_revoked' => 'boolean',
        'expires_at' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'nullable',
        'token' => 'nullable',
        'token_type' => 'nullable',
        'scopes' => 'nullable',
        'is_revoked' => 'nullable',
        'expires_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id', 'user_id');
    }
}
