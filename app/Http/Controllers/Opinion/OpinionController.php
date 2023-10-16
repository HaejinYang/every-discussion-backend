<?php

namespace App\Http\Controllers\Opinion;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Opinion\StoreOpinionRequest;
use App\Models\Opinion;
use App\Util\ArrayUtil;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OpinionController extends ApiController
{
    public function store(StoreOpinionRequest $request)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['topicId', 'title', 'content', 'agreeingType', 'user'], $input), '필드 확인');

        $opinion = Opinion::create([
            'topic_id' => $input['topicId'],
            'title' => $input['title'],
            'content' => $input['content'],
            'agree_type' => $input['agreeingType'],
            'user_id' => $input['user']->id,
            'dislike' => 0,
            'like' => 0,
        ]);

        $isExist = DB::table('participant_topic')->where('topic_id', $input['topicId'])->where('participant_id', $input['user']->id)->count();
        if (!$isExist) {
            DB::table('participant_topic')->insert(['participant_id' => $input['user']->id, 'topic_id' => $input['topicId']]);
        }
        
        return $this->showOne($opinion, Response::HTTP_CREATED);
    }

    public function show(Opinion $opinion)
    {
        return $this->showOne($opinion);
    }
}
