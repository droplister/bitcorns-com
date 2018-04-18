<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class GroupResource extends Resource
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
            'slug' => $this->slug,
            'link' => $this->url,
            'description' => $this->description,
            'crops' => (float) $this->accessBalance(),
            'bitcorn' => (int) $this->rewardBalance(),
            'bitcorn_harvested' => $this->rewards->sum('pivot.total'),
            'member_count' => $this->players->count(),
            'members' => \App\Http\Resources\GroupPlayersCollection::collection($this->players),
        ];
    }
}
