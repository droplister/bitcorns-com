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
        'token_id', 'offset', 'type', 'block_index', 'tx_index', 'tx_hash', 'source', 'destination', 'quantity', 'fee', 'tx_hex', 'confirmed_at', 'processed_at',
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
     * The attributes that are appended.
     *
     * @var array
     */
    protected $appends = [
        'display_fee', 'display_quantity',
    ];

    /**
     * Display Fee
     *
     * @return string
     */
    public function getDisplayFeeAttribute()
    {
        return fromSatoshi($this->fee);
    }

    /**
     * Display Quantity
     *
     * @return string
     */
    public function getDisplayQuantityAttribute()
    {
        return $this->token->divisible ? fromSatoshi($this->quantity) : $this->quantity;
    }

    /**
     * Players
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function players()
    {
        return $this->hasMany(Player::class);
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
}