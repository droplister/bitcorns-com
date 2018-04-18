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
        $tokens = $this->balances()->whereHas('token', function($token){
                $token->whereType('access')->orWhere('type', '=', 'reward');
            })->where('quantity', '>', 0)
            ->get();

        $upgrades = $this->balances()->whereHas('token', function($token){
                $token->whereType('upgrade')->wherePublic('1');
            })->where('quantity', '>', 0)
            ->get();

        $rewards = \DB::table('player_reward')
            ->where('player_id', '=', $this->id)
            ->select('reward_id', 'player_id', \DB::raw('SUM(player_reward.total) as total'), 'txes.tx_index', 'txes.created_at', 'txes.confirmed_at')
            ->groupBy('reward_id', 'player_id')
            ->join('rewards', 'reward_id', '=', 'rewards.id')
            ->join('txes', 'tx_id', '=', 'txes.id')
            ->get();

        return [
            'name' => $this->display_name,
            'address' => $this->address,
            'description' => $this->display_description,
            'link' => $this->url,
            'farm' => $this->display_image_url,
            'access' => $this->accessBalance()->quantity ? true : false,
            'coop' => new \App\Http\Resources\PlayerGroupResource($this->group),
            'tokens' => \App\Http\Resources\PlayerBalanceCollection::collection($tokens),
            'cards' => \App\Http\Resources\PlayerBalanceCollection::collection($upgrades),
            'harvests' => \App\Http\Resources\PlayerRewardsCollection::collection($rewards),
            'position' => [
                'lat' => (float) $this->latitude,
                'lng' => (float) $this->longitude,
            ],
        ];
    }
}
