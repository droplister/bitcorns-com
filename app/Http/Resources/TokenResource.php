<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TokenResource extends Resource
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
            'image' => str_replace('thumb', 'image', $this->thumb_url),
            'asset' => $this->name,
            'description' => $this->content,
            'website' => url('/'),
            'pgpsig' => '',
        ];
    }
}
