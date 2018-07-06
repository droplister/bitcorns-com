<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only('admin');
    }

    public function admin()
    {
        return view('pages.admin');
    }

    /**
     * Show Bitcorner Almanac
     *
     * @return \Illuminate\Http\Response
     */
    public function almanac(Request $request)
    {
        $request->validate([
            'crops' => 'sometimes|numeric|min:0.00000039|max:100',
        ]);

        $crops = $request->input('crops', 0.001);

        return view('pages.almanac', compact('crops'));
    }

    public function map()
    {
        return view('pages.map');
    }
}
