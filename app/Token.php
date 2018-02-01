<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    public static function boot() {
        parent::boot();

        static::creating(function (Token $token) {
            if(in_array($token->type, ['access', 'reward']) && Token::whereType($token->type)->exists())
            {
                throw new \Exception('One ' . ucwords($token->type) . ' Token Allowed');
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'long_name', 'issuer', 'description', 'content', 'image_url', 'thumb_url', 'total_issued', 'divisible', 'locked',
    ];

    /**
     * Get Balances
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function balances()
    {
        return $this->hasMany(Balance::class);
    }

    /**
     * Get Players
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function players()
    {
        return $this->belongsToMany(Player::class, 'balances');
    }

    /**
     * Get Txs
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function txs()
    {
        return $this->hasMany(Tx::class);
    }
}
