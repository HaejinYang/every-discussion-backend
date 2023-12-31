<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Topic\StoreTopicRequest;
use App\Models\Topic;
use App\Services\Topic\TopTopicsService;
use App\Util\ArrayUtil;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TopicController extends ApiController
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $perPage = 10;

        if (is_null($keyword)) {
            $topicsPaginator = TopTopicsService::getTopTopicsWithPagination($perPage);

            return $this->showAll($topicsPaginator);
        }

        $topicsPaginator = TopTopicsService::getTopTopicsWithKeyword($keyword, $perPage);

        return $this->showAll($topicsPaginator);
    }

    public function store(StoreTopicRequest $request)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['title', 'description', 'user'], $input), '필드 확인');

        $topic = Topic::create([
            'title' => $input['title'],
            'description' => $input['description'],
            'user_id' => $input['user']->id,
        ]);

        return $this->showOne($topic, Response::HTTP_CREATED);
    }

    public function show(Topic $topic)
    {
        return $this->showOne($topic);
    }
}
