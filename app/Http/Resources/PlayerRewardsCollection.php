<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PlayerRewardsCollection extends Resource
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
            'date' => $this->confirmed_at ? substr($this->confirmed_at, 0, 10) : substr($this->created_at, 0, 10),
            'total' => (int) $this->total,
            'tx_id' => $this->tx_index,
        ];
    }
}
