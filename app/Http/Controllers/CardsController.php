<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cards = \App\Token::wherePublic(1)->whereType('upgrade')->get();

        return view('cards.index', compact('cards'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Token  $token
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Token $token)
    {
        if($token->type !== 'upgrade') return redirect(route('tokens.show', ['token' => $token->name]));

        $card = $token;
        $holders = $token->balances()->nonZero()->orderBy('quantity', 'desc')->take(20)->get();
        $holders_count = $token->balances()->nonZero()->count();
        $txs_count = $token->txs->count();

        return view('cards.show', compact('card', 'holders', 'holders_count', 'txs_count'));
    }
}