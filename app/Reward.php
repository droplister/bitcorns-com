<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token_id', 'tx_id', 'total', 'per_token',
    ];

    /**
     * Players
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function players()
    {
        return $this->belongsToMany(Player::class)->withPivot('total', 'group_id', 'dry');
    }

    /**
     * Token
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function token()
    {
        return $this->belongsTo(Token::class);
    }

    /**
     * Tx
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tx()
    {
        return $this->belongsTo(Tx::class);
    }

    /**
     * Tx
     */
    public function scopeNotDry($query)
    {
        return $query->where('dry', 0);
    }
}
