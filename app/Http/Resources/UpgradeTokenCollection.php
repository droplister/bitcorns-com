<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UpgradeTokenCollection extends Resource
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
            'link' => $this->url,
            'card' => str_replace('tokens', 'cards', $this->image_url),
            'issued' => $this->total_issued,
            'burned' => $this->total_burned,
            'supply' => $this->total_supply,
        ];
    }
}
