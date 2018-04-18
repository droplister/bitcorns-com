<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TokenCollection extends Resource
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
            'type' => $this->type,
            'issued' => $this->normalized_total,
            'burned' => $this->normalized_burned,
            'supply' => $this->normalized_supply,
            'divisible' => $this->divisible,
            'locked' => $this->locked,
        ];
    }
}
