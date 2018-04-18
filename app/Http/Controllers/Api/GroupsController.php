<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    /**
     * List Groups
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = \App\Group::get();

        return \App\Http\Resources\GroupCollection::collection($groups);
    }

    /**
     * Show Group
     *
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Group $group)
    {
        return new \App\Http\Resources\GroupResource($group);
    }
}
