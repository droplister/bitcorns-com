<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlayersController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:player')->only('editor');
    }

    /**
     * List Players (Sortable)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Validation
        $request->validate(['sort' => 'sometimes|in:access,reward,rewards,rewards-total,no-access,newest,oldest,updated']);

        // Sort Order
        $sort = $request->input('sort', 'access'); // default = 'access'

        // Build Query
        $players = $this->getPlayerQuery($request, $sort)->paginate(45);

        // Return View
        return view('players.index', compact('players', 'sort'));
    }

    /**
     * Show Player
     *
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Player $player)
    {
        // Tokens: Access and Rewards
        $balances = $player->balances()->tokens()->get();

        // Tokens: Upgrades
        $upgrades = $player->balances()->upgrades()->nonZero()->orderBy('quantity', 'desc')->get();

        // Player: Transactions
        $txs = $player->txs()->orderBy('tx_index', 'desc')->take(5)->get();

        // Return View
        return view('players.show', compact('player', 'balances', 'upgrades', 'txs'));
    }

    /**
     * Edit Player
     *
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Player $player)
    {
        // Return View
        return view('players.edit', compact('player'));
    }

    /**
     * Update Player
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\Players\UpdateRequest $request, \App\Player $player)
    {
        // Authentication
        if(\Auth::guard('player')->check() && \Auth::guard('player')->user()->address === $player->address)
        {
            // Logged In
        }
        else
        {
            if($error = $player->guardAgainstInsufficientAccess() || $error = $player->guardAgainstInvalidSignature($request))
            {
                return back()->with('error', $error);
            }
        }

        // Validation
        if($error = $this->guardAgainstBoundaryViolations($request, $player))
        {
            return back()->with('error', $error);
        }

        // Execution
        $player->update($request->all());

        // Return Back
        return back()->with('success', 'Update Complete');
    }

    /**
     * Build Player Query
     *
     * @param  \Illuminate\Http\Request  $request
     */
    private function getPlayerQuery(Request $request, $sort)
    {
        // Search
        if($request->has('q'))
        {
             return \App\Player::whereHasAccess()
                 ->where('name', 'like', '%' . $request->q . '%')
                 ->orWhere('address', 'like', '%' . $request->q . '%');
        }

        // Sorted
        switch($sort)
        {
            case 'access':
                $token = \App\Token::whereType('access')->first();
                return $token->players()->whereHasAccess()->orderBy('quantity', 'desc');
            case 'reward':
                $token = \App\Token::whereType('reward')->first();
                return $token->players()->whereHasAccess()->orderBy('quantity', 'desc');
            case 'rewards':
                return \App\Player::whereHasAccess()->orderBy('rewards_count', 'desc')->orderBy('processed_at', 'asc');
            case 'rewards-total':
                return \App\Player::whereHasAccess()->orderBy('rewards_total', 'desc');
            case 'no-access':
                return \App\Player::whereHasNoAccess()->orderBy('processed_at', 'desc');
            case 'newest':
                return \App\Player::whereHasAccess()->orderBy('processed_at', 'desc');
            case 'oldest':
                return \App\Player::whereHasAccess()->orderBy('processed_at', 'asc');
            case 'updated':
                return \App\Player::whereHasAccess()->orderBy('updated_at', 'desc');
            default:
                \Exception('Sort Validation Failure');
        }
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
}