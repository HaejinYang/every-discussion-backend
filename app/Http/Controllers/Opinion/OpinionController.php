<?php

namespace App\Http\Controllers\Opinion;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Opinion\StoreOpinionRequest;
use App\Models\Opinion;
use App\Util\ArrayUtil;
use Illuminate\Http\Response;

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
        ]);

        return $this->showOne($opinion, Response::HTTP_CREATED);
    }

    public function show(Opinion $opinion)
    {
        return $this->showOne($opinion);
    }
}
