<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TokensController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tokens = \App\Token::wherePublic(1)->get();

        return \App\Http\Resources\TokenCollection::collection($tokens);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Token  $token
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Token $token)
    {
        return new \App\Http\Resources\TokenResource($token);
    }
}
