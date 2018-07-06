<?php

namespace App;

use App\Traits\Moderatable;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use Moderatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'group_id',
         'player_id',
         'description',
         'accepted_at',
         'rejected_at',
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
        'accepted_at',
        'rejected_at',
    ];

    /**
     * Group
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
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
}
