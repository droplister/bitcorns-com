<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tx extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token_id', 'type', 'block_index', 'tx_index', 'tx_hash', 'source', 'destination', 'quantity', 'fee', 'tx_hex', 'confirmed_at', 'processed_at',
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
        'confirmed_at', 'processed_at',
    ];

    /**
     * Get Player
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function player()
    {
        return $this->hasOne(Player::class);
    }

    /**
     * Get Token
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function token()
    {
        return $this->belongsTo(Token::class);
    }
}