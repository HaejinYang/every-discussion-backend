<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\ApiController;
use App\Models\Topic;
use App\Services\Topic\TopicService;
use Illuminate\Http\Request;

class TopicOpinionController extends ApiController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Topic $topic)
    {
        $keyword = $request->input('keyword');
        $topicService = new TopicService($topic);

        if (is_null($keyword)) {
            return $this->showAll($topicService->opinions());
        }

        return $this->showAll($topicService->searchOpinions($keyword));
    }
}
