<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'player_id', 'token_id', 'quantity',
    ];

    /**
     * The attributes that are appended.
     *
     * @var array
     */
    protected $appends = [
        'display_quantity',
    ];

    /**
     * Display Quantity
     *
     * @return string
     */
    public function getDisplayQuantityAttribute()
    {
        return $this->token->divisible ? fromSatoshi($this->quantity) : number_format($this->quantity);
    }

    /**
     * Player
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
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

    public function scopeNonZero($query)
    {
        return $query->where('quantity', '>', 0);
    }
}
