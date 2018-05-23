<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $players = \App\Player::whereHasAccess()->get();

        return \App\Http\Resources\PlayerCollection::collection($players);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Player $player
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Player $player)
    {
        return new \App\Http\Resources\PlayerResource($player);
    }
}
