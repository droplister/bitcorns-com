<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GroupsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only('update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = \App\Group::withCount('players')->orderBy('players_count', 'desc')->orderBy('name', 'asc')->get();

        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Groups\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\Groups\StoreRequest $request)
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

        $group = \App\Group::create([
            'player_id' => $player->id,
            'type' => 'open',
            'name' => $request->name,
            'description' => $request->description,
        ]);

        \App\Membership::create([
            'group_id' => $group->id,
            'player_id' => $player->id,
            'description' => 'Early Alpha Group Admin',
            'accepted_at' => \Carbon\Carbon::now(),
        ]);

        $player->update(['group_id' => $group->id]);

        return redirect(route('groups.index'))->with('success', 'Group Established');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Group $group)
    {
        $players = $group->players()->withCount('rewards')->get();

        return view('groups.show', compact('group', 'players'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Player $player)
    {
        return view('groups.edit', compact('player'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Minimum access token balance required.
     *
     * @param  \App\Player  $player
     */
    private function guardAgainstInsufficientAccess(\App\Player $player)
    {
        if($player->accessBalance()->quantity < env('MIN_ACCESS_GROUP'))
        {
            return 'Low Access Token Balance';
        }
    }

    /**
     * Verify Signature
     *
     * @param  \App\Http\Requests\Groups\StoreRequest  $request
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    private function guardAgainstInvalidSignature(\App\Http\Requests\Groups\StoreRequest $request, \App\Player $player)
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
