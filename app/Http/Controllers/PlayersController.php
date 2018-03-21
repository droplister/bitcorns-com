<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'sort' => 'sometimes|in:access,reward,rewards,rewards-total,no-access,newest,oldest,updated',
        ]);

        $sort = $request->input('sort', 'access');

        switch($sort) {
            case 'access':
                $token = \App\Token::whereType('access')->first();
                $players = $token->players()->whereHasAccess()->orderBy('quantity', 'desc')->get();
                break;
            case 'reward':
                $token = \App\Token::whereType('reward')->first();
                $players = $token->players()->whereHasAccess()->orderBy('quantity', 'desc')->get();
                break;
            case 'rewards':
                $players = \App\Player::whereHasAccess()->orderBy('rewards_count', 'desc')->orderBy('processed_at', 'asc')->get();
                break;
            case 'rewards-total':
                $players = \App\Player::whereHasAccess()->orderBy('rewards_total', 'desc')->get();
                break;
            case 'no-access':
                $players = \App\Player::whereHasNoAccess()->orderBy('processed_at', 'desc')->get();
                break;
            case 'newest':
                $players = \App\Player::whereHasAccess()->orderBy('processed_at', 'desc')->get();
                break;
            case 'oldest':
                $players = \App\Player::whereHasAccess()->orderBy('processed_at', 'asc')->get();
                break;
            case 'updated':
                $players = \App\Player::whereHasAccess()->orderBy('updated_at', 'desc')->get();
                break;
            default:
                \Exception('Sort Validation Failure');
                break;
        }

        if($request->has('q'))
        {
             $players = \App\Player::whereHasAccess()->where('name', 'like', '%' . $request->q . '%')->orWhere('address', 'like', '%' . $request->q . '%')->get();
        }

        return view('players.index', compact('players', 'sort'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Player $player)
    {
        $balances = $player->balances()->whereHas('token', function($token){
            $token->whereType('access');
        })->get();

        $upgrades = $player->balances()->whereHas('token', function($token){
            $token->whereType('upgrade');
        })->get();

        return view('players.show', compact('player', 'balances', 'upgrades'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Player $player)
    {
        return view('players.edit', compact('player'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\Players\UpdateRequest $request, \App\Player $player)
    {
        if($error = $this->guardAgainstInsufficientAccess($player))
        {
            return back()->with('error', $error);
        }

        if($error = $this->guardAgainstInvalidSignature($request, $player))
        {
            return back()->with('error', $error);
        }

        if($error = $this->guardAgainstBoundaryViolations($request, $player))
        {
            return back()->with('error', $error);
        }

        $player->update($request->all());

        return back()->with('success', 'Update Complete');
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

        return false;
    }

    /**
     * Circles Have Boundaries
     *
     * @param  \App\Http\Requests\Players\UpdateRequest  $request
     * @param  \App\Player  $player
     */
    private function guardAgainstBoundaryViolations(\App\Http\Requests\Players\UpdateRequest $request, \App\Player $player)
    {
        if($request->has('latitude') && $request->has('longitude'))
        {
            if($request->latitude === null) return false;

            $unit = 6378100; // meters
            $nearby_player = \App\Player::select(\DB::raw("*,
               ($unit * ACOS(COS(RADIANS($request->latitude))
                      * COS(RADIANS(latitude))
                      * COS(RADIANS($request->longitude) - RADIANS(longitude))
                      + SIN(RADIANS($request->latitude))
                      * SIN(RADIANS(latitude)))) AS distance")
               )->whereNotNull('latitude')
                ->whereNotIn('id', [$player->id])
                ->orderBy('distance','asc')
                ->first();

            if($nearby_player)
            {
                $distance_between = distance($request->latitude, $request->longitude, $nearby_player->latitude, $nearby_player->longitude); // meters
                $distance_required = $player->map_radius + $nearby_player->map_radius; // meters

                \Storage::append('testing.log', $distance_between);
                \Storage::append('testing.log', $distance_required);

                if($distance_between < $distance_required)
                {
                    return 'No Tresspassing (' . $nearby_player->name . ')';
                }
            }
        }
    }

    /**
     * Verify Signature
     *
     * @param  \App\Http\Requests\Players\UpdateRequest  $request
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    private function guardAgainstInvalidSignature(\App\Http\Requests\Players\UpdateRequest $request, \App\Player $player)
    {
        try
        {
            $timestamp = \Carbon\Carbon::parse($request->timestamp);

            if($timestamp < \Carbon\Carbon::now()->subHour())
            {
                return 'Invalid Timestamp';
            }
        }
        catch(\Exception $e)
        {
            return 'Invalid Timestamp';
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
                return 'Invalid Signature';
            }
        }
        catch(\Exception $e)
        {
            return 'Invalid Signature';
        }

        return false;
    }
}