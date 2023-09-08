<?php

namespace App\Services\Topic;

use App\Models\Topic;
use Illuminate\Support\Collection;

class TopicService
{
    private Topic $topic;

    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
    }

    public function opinions(): Collection
    {
        return $this->agreeOpinions()->concat($this->disagreeOpinions());
    }

    public function agreeOpinions(): Collection
    {
        return $this->topic->opinions()->where('agree_type', 'agree')->get();
    }

    public function disagreeOpinions(): Collection
    {
        return $this->topic->opinions()->where('agree_type', 'disagree')->get();
    }

    public function searchOpinions(string $keyword): Collection
    {
        return $this->topic->opinions()->whereFullText('title', $keyword . "*", ['mode' => 'boolean'])->orderBy('like', 'desc')->get();
    }
}
