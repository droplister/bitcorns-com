<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PlayerCollection extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $cards_count = $this->balances()->whereHas('token', function($token){
                $token->whereType('upgrade');
                $token->wherePublic('1');
            })->where('quantity', '>', 0)
            ->count();

        return [
            'name' => $this->display_name,
            'address' => $this->address,
            'description' => $this->display_description,
            'link' => $this->url,
            'farm' => $this->display_image_url,
            'coop' => $this->group ? $this->group->name : null,
            'cards' => $cards_count,
            'access' => $this->accessBalance()->quantity ? true : false,
        ];
    }
}
