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
            'name' => $this->name,
            'date' => $this->tx->display_confirmed_at,
            'description' => $this->description,
            'href' => $this->url,
            'image' => $this->display_image_url,
            'thumb' => $this->display_thumb_url,
            'options' => [
                'editable' => false,
                'strokeColor' => '#000000',
                'fillColor' => '#FFFFFF',
            ],
            'position' => [
                'lat' => (float) $this->latitude,
                'lng' => (float) $this->longitude,
            ],
            'radius' => $this->map_radius,
        ];
    }
}
