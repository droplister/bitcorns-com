<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PlayerBalanceCollection extends Resource
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
            'name' => $this->token->display_name,
            'balance' => (float) str_replace(',', '', $this->display_quantity),
        ];
    }
}
