<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'long_name', 'name', 'issuer', 'content', 'description', 'image_url', 'thumb_url', 'total_issued', 'total_burned', 'divisible', 'locked', 'public',
    ];

    /**
     * The attributes that are appended.
     *
     * @var array
     */
    protected $appends = [
        'total_supply',
        'normalized_total', 'normalized_burn', 'normalized_supply',
        'display_name', 'display_total', 'display_image_url', 'display_thumb_url',
        'url', 'edit_url', 'explorer_url',
    ];

    /**
     * Total Supply
     *
     * @return string
     */
    public function getTotalSupplyAttribute()
    {
        return $this->total_issued - $this->total_burned;
    }

    /**
     * Normalized Total
     *
     * @return string
     */
    public function getNormalizedTotalAttribute()
    {
        return $this->divisible ? (float) fromSatoshi($this->total_issued) : $this->total_issued;
    }

    /**
     * Normalized Burned
     *
     * @return string
     */
    public function getNormalizedBurnedAttribute()
    {
        return $this->divisible ? (float) fromSatoshi($this->total_burned) : $this->total_burned;
    }

    /**
     * Normalized Supply
     *
     * @return string
     */
    public function getNormalizedSupplyAttribute()
    {
        if($this->type == 'reward')
        {
            return $this->rewards()->sum('total') - $this->total_burned;
        }

        return $this->divisible ? (float) fromSatoshi($this->total_issued - $this->total_burned) : $this->total_issued - $this->total_burned;
    }

    /**
     * Display Name
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return $this->long_name ? $this->long_name : $this->name;
    }

    /**
     * Display Total
     *
     * @return string
     */
    public function getDisplayTotalAttribute()
    {
        return $this->divisible ? fromSatoshi($this->total_issued) : number_format($this->total_issued);
    }

    /**
     * Display Image Url
     *
     * @return string
     */
    public function getDisplayImageUrlAttribute()
    {
        return $this->image_url ? $this->image_url : env('DEFAULT_TOKEN_IMAGE');
    }

    /**
     * Display Thumb Url
     *
     * @return string
     */
    public function getDisplayThumbUrlAttribute()
    {
        return $this->thumb_url ? $this->thumb_url : env('DEFAULT_TOKEN_THUMB');
    }

    /**
     * Url
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return route('tokens.show', ['token' => $this->name]);
    }

    /**
     * Edit Url
     *
     * @return string
     */
    public function getEditUrlAttribute()
    {
        return route('tokens.edit', ['token' => $this->name]);
    }

    /**
     * Explorer Url
     *
     * @return string
     */
    public function getExplorerUrlAttribute()
    {
        return 'https://xchain.io/asset/' . $this->name;
    }

    /**
     * Balances
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function balances()
    {
        return $this->hasMany(Balance::class)->with('player');
    }

    /**
     * Players
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function players()
    {
        return $this->belongsToMany(Player::class, 'balances')->withPivot('quantity');
    }

    /**
     * Rewards
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rewards()
    {
        return $this->hasMany(Reward::class);
    }

    /**
     * Txs
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function txs()
    {
        return $this->hasMany(Tx::class);
    }

    /**
     * Enforce Type Limit
     */
    public static function boot() {
        static::creating(function (Token $token) {
            if(in_array($token->type, ['access', 'reward']) && Token::whereType($token->type)->exists()) {
                throw new \Exception('Token Limit Exceeded');
            }
        });
        parent::boot();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }
}