<?php

namespace App\Http\Controllers;

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
        $topicService = new TopicService($topic);

        return $this->showAll($topicService->opinions());
    }
}
