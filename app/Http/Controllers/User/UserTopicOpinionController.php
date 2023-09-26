<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Models\Participant;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserTopicOpinionController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request, Participant $user, Topic $topic)
    {
        $inputUser = $request->input('user');

        assert(!is_null($inputUser), "유저가 없습니다.");

        if ($user->id !== $inputUser->id) {
            return $this->error("다른 유저의 정보를 볼 수 없습니다.", Response::HTTP_FORBIDDEN);
        }

        $result = $user->opinions()->where('topic_id', $topic->id)->get();

        return $this->showAll($result);
    }
}
