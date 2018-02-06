<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PlayerResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->display_name,
            'address' => $this->address,
            'content' => $this->content,
            'url' => url(route('players.show', ['player' => $this->address])),
            'image_url' => $this->display_image_url,
            'balances' => $this->balances,
        ];
    }
}
