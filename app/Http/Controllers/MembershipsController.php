<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MembershipsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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

        if($error = $this->guardAgainstInsufficientAccess($player))
        {
            return back()->with('error', $error);
        }

        if($error = $this->guardAgainstInvalidSignature($request, $player))
        {
            return back()->with('error', $error);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        if($error = $this->guardAgainstInsufficientAccess($player))
        {
            return back()->with('error', $error);
        }

        if($error = $this->guardAgainstInvalidSignature($request, $player))
        {
            return back()->with('error', $error);
        }

        $player->update(['group_id' => null]);

        return back()->with('success', 'Left Group');
    }

    /**
     * Minimum access token balance required.
     *
     * @param  \App\Player  $player
     */
    private function guardAgainstInsufficientAccess(\App\Player $player)
    {
        if($player->accessBalance()->quantity < env('MIN_ACCESS_UPDATE'))
        {
            return 'Low Access Token Balance';
        }
    }

    /**
     * Verify Signature
     *
     * @param  $request
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    private function guardAgainstInvalidSignature($request, \App\Player $player)
    {
        try
        {
            $timestamp = \Carbon\Carbon::parse($request->timestamp);
        }
        catch(\Exception $e)
        {
            return 'Invalid Timestamp';
        }

        if($timestamp < \Carbon\Carbon::now()->subHour())
        {
            return 'Expired Timestamp';
        }

        try
        {
            $messageVerification = \BitWasp\BitcoinLib\BitcoinLib::verifyMessage(
                $player->address,
                $request->signature,
                $request->timestamp
            );

            if(! $messageVerification)
            {
                return 'No Message Verification';
            }
        }
        catch(\Exception $e)
        {
            return 'Invalid Bitcoin Address';
        }

        return false;
    }
}
