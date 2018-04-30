<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CardsController extends Controller
{
    /**
     * List Cards
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cards = \App\Token::whereType('upgrade')->wherePublic(1)->oldest()->get();

        return \App\Http\Resources\UpgradeTokenCollection::collection($cards);
    }

    /**
     * Show Card
     *
     * @return \Illuminate\Http\Response
     */
    public function show($card)
    {
        $card = \App\Token::whereName($card)
            ->whereType('upgrade')
            ->wherePublic(1)
            ->orWhere('long_name', '=', $card)
            ->whereType('upgrade')
            ->wherePublic(1)
            ->first();

        if(! $card) return ['error' => 'Card not found.'];

        return new \App\Http\Resources\UpgradeTokenResource($card);
    }
}
