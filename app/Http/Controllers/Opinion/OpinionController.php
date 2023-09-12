<?php

namespace App\Http\Controllers\Opinion;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Opinion\StoreOpinionRequest;
use App\Models\Opinion;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OpinionController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOpinionRequest $request)
    {
        $input = $request->validated();
        $userId = 1;
        $opinion = Opinion::create([
            'topic_id' => $input['topicId'],
            'title' => $input['title'],
            'content' => $input['content'],
            'agree_type' => $input['agreeingType'],
            'user_id' => $userId,
        ]);

        return $this->showOne($opinion, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Opinion $opinion)
    {
        return $this->showOne($opinion);
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
