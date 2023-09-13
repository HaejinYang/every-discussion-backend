<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Models\Participant;
use Illuminate\Http\Request;

class UserTopicController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Participant $user)
    {
        return $this->showAll($user->topics()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
