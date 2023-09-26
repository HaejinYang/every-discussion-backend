<?php

namespace App\Http\Controllers\Opinion;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Opinion\StoreOpinionRequest;
use App\Models\Opinion;
use Illuminate\Http\Response;

class OpinionController extends ApiController
{
    public function store(StoreOpinionRequest $request)
    {
        $input = $request->input();
        $topicId = $input['topicId'];
        $title = $input['title'];
        $content = $input['content'];
        $agreeType = $input['agreeingType'];
        $user = $input['user'];

        assert(!is_null($topicId) && !is_null($title) && !is_null($content) && !is_null($agreeType) && !is_null($user), "빠진 필드가 있습니다.");

        $userId = $user->id;
        $opinion = Opinion::create([
            'topic_id' => $topicId,
            'title' => $title,
            'content' => $content,
            'agree_type' => $agreeType,
            'user_id' => $userId,
        ]);

        return $this->showOne($opinion, Response::HTTP_CREATED);
    }

    public function show(Opinion $opinion)
    {
        return $this->showOne($opinion);
    }
}
