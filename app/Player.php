<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id', 'tx_id', 'address', 'name', 'content', 'image_url', 'rewards_total', 'meta', 'processed_at',
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
        'processed_at',
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
     * Get Rewards
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rewards()
    {
        return $this->belongsToMany(Reward::class)->withPivot('total');
    }

    /**
     * Get Tokens
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tokens()
    {
        return $this->belongsToMany(Token::class, 'balances');
    }

    /**
     * Get Tx
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tx()
    {
        return $this->belongsTo(Tx::class);
    }

    /**
     * Get Txs
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function txs()
    {
        return $this->hasMany(Tx::class, 'source');
    }
}
