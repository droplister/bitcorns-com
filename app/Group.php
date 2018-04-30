<?php

namespace App;

use App\Traits\Moderatable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasSlug, Moderatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'player_id', 'type', 'name', 'slug', 'description', 'rejected_at',
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
        'rejected_at',
    ];

    /**
     * These attributes are dynamically added.
     *
     * @var array
     */
    protected $appends = [
        'url',
    ];

    /**
     * Url
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return route('groups.show', ['group' => $this->slug]);
    }

    /**
     * Balances
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function balances()
    {
        return $this->hasManyThrough(Balance::class, Player::class);
    }

    /**
     * Memberships
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memberships()
    {
        return $this->hasMany(Membership::class);
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
     * Players
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function players()
    {
        return $this->hasMany(Player::class);
    }

    /**
     * Rewards
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rewards()
    {
        return $this->belongsToMany(Reward::class, 'player_reward', 'group_id', 'reward_id')->withPivot('total', 'player_id', 'dry');
    }

    public function accessBalance()
    {
        $token = \App\Token::whereType('access')->first();
        return fromSatoshi($this->balances()->where('token_id', $token->id)->sum('quantity'));
    }

    public function rewardBalance()
    {
        $token = \App\Token::whereType('reward')->first();
        return $this->balances()->where('token_id', $token->id)->sum('quantity');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}