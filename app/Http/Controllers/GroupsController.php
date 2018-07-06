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
        $groups = \App\Group::has('players')->withCount('players')
            ->orderBy('players_count', 'desc')
            ->orderBy('name', 'asc')
            ->get();

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

        if(\Auth::guard('player')->check() && \Auth::guard('player')->user()->address === $player->address)
        {
            // We Good
        }
        else
        {
            if($error = $player->guardAgainstInsufficientAccess(env('MIN_ACCESS_GROUP')) || $error = $player->guardAgainstInvalidSignature($request))
            {
                return back()->with('error', $error);
            }
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
        $players = $group->players()
            ->whereHasAccess()
            ->withCount('rewards')
            ->get();

        return view('groups.show', compact('group', 'players'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function showTwo(\App\Group $group)
    {
        $players = $group->players()
            ->whereHasAccess()
            ->withCount('rewards')
            ->get();

        $ids = $players->pluck('id')->toArray();

        $upgrades = \App\Balance::whereIn('player_id', $ids)
            ->upgrades()
            ->nonZero()
            ->selectRaw('SUM(quantity) as quantity, token_id')
            ->groupBy('token_id')
            ->orderBy('quantity', 'desc')
            ->get();

        return view('groups.show-2', compact('group', 'players', 'upgrades'));
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
}
