<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['index', 'update']);
    }

    /**
     * Moderate Uploads
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uploads = \App\Upload::pending()->orderBy('created_at', 'asc')->get();

        return view('uploads.index', compact('uploads'));
    }

    /**
     * Upload Image
     *
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function create(\App\Player $player)
    {
        return view('uploads.create', compact('player'));
    }

    /**
     * Store Upload
     *
     * @param  \App\Http\Requests\Uploads\StoreRequest  $request
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\Uploads\StoreRequest $request, \App\Player $player)
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

        $stored_file = $request->file('image')->store('public/custom');
        $stored_path = str_replace('public', 'storage', $stored_file);

        \App\Upload::create([
            'player_id' => $player->id,
            'new_image_url' => asset($stored_path),
            'old_image_url' => $player->image_url,
        ]);

        $player->update([
            'image_url' => asset($stored_path),
        ]);

        return back()->with('success', 'Update Complete');
    }

    /**
     * Moderate Upload
     *
     * @param  \App\Http\Requests\Uploads\UpdateRequest  $request
     * @param  \App\Upload $upload
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\Uploads\UpdateRequest $request, \App\Upload $upload)
    {
        if('accept' === $request->action)
        {
            $upload->accept();

            if($upload->player->image_url != $upload->new_image_url)
            {
                $upload->player->update([
                    'image_url' => $upload->new_image_url,
                ]);
            }
        }

        if('reject' === $request->action)
        {
            $upload->reject();

            if($upload->player->image_url == $upload->new_image_url)
            {
                $upload->player->update([
                    'image_url' => $upload->old_image_url,
                ]);
            }
        }

        return back()->with('success', 'Upload Moderated');
    }

    /**
     * Minimum access token balance required.
     *
     * @param  \App\Player  $player
     */
    private function guardAgainstInsufficientAccess(\App\Player $player)
    {
        if($player->accessBalance()->quantity < env('MIN_ACCESS_UPLOAD'))
        {
            return 'Low Access Token Balance';
        }
    }

    /**
     * Verify Signature
     *
     * @param  \App\Http\Requests\Uploads\StoreRequest  $request
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    private function guardAgainstInvalidSignature(\App\Http\Requests\Uploads\StoreRequest $request, \App\Player $player)
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