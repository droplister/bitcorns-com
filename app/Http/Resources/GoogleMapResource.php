<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class GoogleMapResource extends Resource
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
            'date' => $this->tx->display_confirmed_at,
            'href' => $this->url,
            'options' => [
                'editable' => false,
                'strokeColor' => '#000000',
                'fillColor' => '#FFFFFF',
            ],
            'position' => [
                'lat' => (float) $this->latitude,
                'lng' => (float) $this->longitude,
            ],
            'radius' => round($this->map_radius),
        ];
    }
}
