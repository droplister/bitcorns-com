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
        $cards = \App\Token::where('public', '=', 1)
            ->whereType('upgrade')
            ->oldest()
            ->get();

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
        if($token->type !== 'upgrade')
        {
            return redirect(route('tokens.show', ['token' => $token->name]));
        }

        $card = $token; // Re-assign

        $players = $card->players()->whereHasAccess()
            ->where('quantity', '>', 0)
            ->orderBy('quantity', 'desc')
            ->paginate(20);

        $txs_count = $card->txs->count();

        return view('cards.show', compact('card', 'players', 'txs_count'));
    }
}