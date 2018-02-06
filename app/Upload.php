<?php

namespace App;

use App\Traits\Moderatable;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use Moderatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'player_id', 'new_image_url', 'old_image_url', 'accepted_at', 'rejected_at',
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
        'accepted_at', 'rejected_at',
    ];

    /**
     * Player
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function scopeAccepted($query)
    {
        return $query->whereNotNull('accepted_at')->whereNull('rejected_at');
    }

    public function scopePending($query)
    {
        return $query->whereNull('accepted_at')->whereNull('rejected_at');
    }

    public function scopeRejected($query)
    {
        return $query->whereNull('accepted_at')->whereNotNull('rejected_at');
    }
}