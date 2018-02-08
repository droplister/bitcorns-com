<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GoogleMapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $players = \App\Player::whereNotNull('latitude')->whereHasAccess()->get();

        return \App\Http\Resources\GoogleMapResource::collection($players);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Group $group)
    {
        $players = $group->players()->whereNotNull('latitude')->whereHasAccess()->get();

        return \App\Http\Resources\GoogleMapResource::collection($players);
    }
}
