<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\UserTopic\UserTopicShowRequest;
use App\Models\Participant;
use App\Services\Topic\TopTopicsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserTopicController extends ApiController
{
    public function __invoke(Request $request, Participant $user)
    {
        assert(!is_null($user), '유저 필드가 필요합니다.');
        $keyword = $request->input('keyword');
        $perPage = 5;

        // 유저가 생성한 토픽
        $result = DB::select("SELECT id FROM topics WHERE user_id = :user_id", ['user_id' => $user->id]);
        $topicsOfHost = array_column($result, 'id');

        // 유저가 참여한 토픽
        $topicIds = $user->topics()->pluck('id');
        $topicIds = array_merge($topicIds->toArray(), $topicsOfHost);
        $topicIds = array_unique($topicIds);

        if (is_null($keyword)) {
            $topicsPaginator = TopTopicsService::getTopTopicsWithIds($topicIds, $perPage);

            return $this->showAll($topicsPaginator);
        }

        $topicsPaginator = TopTopicsService::getTopTopicsWithIdsAndKeyword($keyword, $topicIds, $perPage);

        return $this->showAll($topicsPaginator);
    }
}
