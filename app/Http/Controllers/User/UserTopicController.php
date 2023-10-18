<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\UserTopic\UserTopicShowRequest;
use App\Models\Participant;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;

class UserTopicController extends ApiController
{
    public function __invoke(Participant $user)
    {
        assert(!is_null($user), '유저 필드가 필요합니다.');

        $topicIds = $user->topics()->pluck('id');
        $topicsOfHost = DB::select("SELECT id FROM topics WHERE user_id = :user_id", ['user_id' => $user->id]);
        $topicsOfHost = array_column($topicsOfHost, 'id');
        $topicIds = array_merge($topicIds->toArray(), $topicsOfHost);
        $topicIds = array_unique($topicIds);

        $topics = Topic::whereIn('id', $topicIds)->get();
        return $this->showAll($topics)
    }
}
