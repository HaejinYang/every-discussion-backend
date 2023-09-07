<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;

class TopicOpinionController extends ApiController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Topic $topic)
    {
        $opinions = $topic->opinions()->get();

        return $this->showAll($opinions);
    }
}
