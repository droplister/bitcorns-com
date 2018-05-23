<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArcadeController extends Controller
{
    /**
     * List Cards
     *
     * @return \Illuminate\Http\Response
     */
    public function showMemoryGame()
    {
        $cards = \App\Token::whereType('upgrade')->wherePublic(1)->inRandomOrder()->take(7)->get();

        return \App\Http\Resources\UpgradeTokenCollection::collection($cards);
    }
}
