<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TokensController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tokens = \App\Token::wherePublic(1)->where('type', '!=', 'upgrade')->orderBy('type', 'asc')->get();

        return view('tokens.index', compact('tokens'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Token  $token
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Token $token)
    {
        if($token->type == 'upgrade') return redirect(route('cards.show', ['token' => $token->name]));

        $holders = $token->balances()->nonZero()->orderBy('quantity', 'desc')->take(20)->get();
        $holders_count = $token->balances()->nonZero()->count();
        $txs_count = $token->txs->count();

        return view('tokens.show', compact('token', 'holders', 'holders_count', 'txs_count'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Token  $token
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Token $token)
    {
        return view('tokens.edit', compact('token'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Tokens\UpdateRequest  $request
     * @param  \App\Token  $token
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\Tokens\UpdateRequest $request, \App\Token $token)
    {
        $token->update($request->all());

        return back()->with('success', 'Update Complete');
    }
}