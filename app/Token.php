<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    public static function boot() {
        parent::boot();

        static::creating(function (Token $token) {
            if(Token::whereType($token->type)->exists()) throw new Exception('One ' . ucwords($token->type) . ' Token Allowed');
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'name', 'long_name', 'owner', 'issuer', 'description', 'content', 'image_url', 'thumb_url', 'total_issued', 'divisible', 'locked',
    ];
}
