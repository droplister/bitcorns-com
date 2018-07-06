<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class UnlockController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:player')->except('destroy');
    }

    /**
     * Show the Unlock Form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(\Illuminate\Http\Request $request)
    {
        return view('auth.unlock', compact('request'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\Unlock\StoreRequest $request)
    {
        // Get Player
        $player = \App\Player::whereAddress($request->address)->first();

        // Check Data
        if($error = $player->guardAgainstInsufficientAccess() || $error = $player->guardAgainstInvalidSignature($request))
        {
            return back()->with('error', $error);
        }

        // Override Configuration
        config(['session.lifetime' => $request->lifetime]);

        // Login Without Password
        if(\Auth::guard('player')->loginUsingId($player->id))
        {
            return redirect()->route('players.edit', ['player' => $player->address])->with('success', 'Your farm is now unlocked!');
        }
    }

    /**
     * Unlock
     */
    public function destroy(\Illuminate\Http\Request $request)
    {
        $request->session()->invalidate();

        return redirect()->back();
    }
}
