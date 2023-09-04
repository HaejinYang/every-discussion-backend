<?php

namespace App\Services\Topic;

use App\Models\Topic;

class TopicService
{
    private Topic $topic;

    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
    }
}
