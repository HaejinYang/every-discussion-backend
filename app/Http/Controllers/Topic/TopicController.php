<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\ApiController;
use App\Models\Topic;
use App\Services\Topic\TopTopicsService;
use Illuminate\Http\Request;

class TopicController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $perPage = 10;

        if (is_null($keyword)) {
            $topicsPaginator = TopTopicsService::getTopTopicsWithPagination($perPage);

            return $this->showAll($topicsPaginator)->withCookie('testkey', 'testvalue', 1);
        }

        $topicsPaginator = TopTopicsService::getTopTopicsWithKeyword($keyword, $perPage);

        return $this->showAll($topicsPaginator);

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
    public function show(Topic $topic)
    {
        return $topic;
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
