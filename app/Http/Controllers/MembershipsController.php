<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MembershipsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Memberships\StoreRequest $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\Memberships\StoreRequest $request, \App\Group $group)
    {
        $player = \App\Player::whereAddress($request->address)->first();

        if(\Auth::guard('player')->check() && \Auth::guard('player')->user()->address === $player->address)
        {
            // We Good
        }
        else
        {
            if($error = $player->guardAgainstInsufficientAccess() || $error = $player->guardAgainstInvalidSignature($request))
            {
                return back()->with('error', $error);
            }
        }

        \App\Membership::create([
            'group_id' => $group->id,
            'player_id' => $player->id,
            'description' => 'Early Alpha Group Member',
            'accepted_at' => \Carbon\Carbon::now(),
        ]);

        $player->update(['group_id' => $group->id]);

        return redirect(route('groups.index'))->with('success', 'Joined Group');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Player $player)
    {
        return view('memberships.edit', compact('player'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\Memberships\DestroyRequest $request
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\Http\Requests\Memberships\DestroyRequest $request, \App\Player $player)
    {
        if(\Auth::guard('player')->check() && \Auth::guard('player')->user()->address === $player->address)
        {
            // We Good
        }
        else
        {
            if($error = $player->guardAgainstInsufficientAccess() || $error = $player->guardAgainstInvalidSignature($request))
            {
                return back()->with('error', $error);
            }
        }

        $player->update(['group_id' => null]);

        return back()->with('success', 'Left Group');
    }
}
