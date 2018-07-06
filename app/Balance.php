<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => \App\Events\BalanceWasCreated::class,
        'updated' => \App\Events\BalanceWasUpdated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'player_id',
         'token_id',
         'quantity',
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

    /**
     * Scope Tokens
     */
    public function scopeTokens($query)
    {
        return $query->whereHas('token', function($token) {
            $token->where('type', '=', 'access');
            $token->orWhere('type', '=', 'reward');
        });
    }

    /**
     * Scope Upgrades
     */
    public function scopeUpgrades($query)
    {
        return $query->whereHas('token', function($token) {
            $token->where('type', '=', 'upgrade');
            $token->where('public', '=', '1');
        });
    }

    /**
     * Scope Non-Zero
     */
    public function scopeNonZero($query)
    {
        return $query->where('quantity', '>', 0);
    }
}