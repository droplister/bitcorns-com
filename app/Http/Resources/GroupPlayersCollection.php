<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class GroupPlayersCollection extends Resource
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
            'link' => $this->url,
            'farm' => $this->display_image_url,
            'access' => $this->accessBalance()->quantity ? true : false,
        ];
    }
}
