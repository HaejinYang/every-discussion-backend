<?php

namespace App\Http\Controllers\Opinion;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Opinion\StoreOpinionRequest;
use App\Jobs\SummarizeJob;
use App\Models\Opinion;
use App\Util\ArrayUtil;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Diff\Exception;

class OpinionController extends ApiController
{
    public function store(StoreOpinionRequest $request)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['topicId', 'title', 'content', 'agreeingType', 'user'], $input), '필드 확인');

        DB::beginTransaction();
        try {
            $opinion = Opinion::create([
                'topic_id' => $input['topicId'],
                'title' => $input['title'],
                'content' => $input['content'],
                'agree_type' => $input['agreeingType'],
                'user_id' => $input['user']->id,
                'dislike' => 0,
                'like' => 0,
                'summary' => $input['content']
            ]);

            $isExist = DB::table('participant_topic')->where('topic_id', $input['topicId'])->where('participant_id', $input['user']->id)->count();
            if (!$isExist) {
                DB::table('participant_topic')->insert(['participant_id' => $input['user']->id, 'topic_id' => $input['topicId']]);
            }

            if (isset($input['addTo'])) {
                $addToId = $input['addTo'];

                DB::table('opinions_reference')->insert(['opinion_id' => $opinion->id, 'refer_to_id' => $addToId]);
            }

            DB::commit();

            SummarizeJob::dispatch($input['content'], $opinion->id);
        } catch (e) {
            DB::rollBack();
        }

        return $this->showOne($opinion, Response::HTTP_CREATED);
    }

    public function show(Opinion $opinion)
    {
        return $this->showOne($opinion);
    }

    public function delete(Request $request, Opinion $opinion)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['user'], $input), '필드 확인');

        $userId = $opinion->user_id;
        $topicId = $opinion->topic_id;
        $user = $input['user'];

        if ($user->id !== $userId) {
            return $this->showMessage('의견 삭제 권리 없음', Response::HTTP_FORBIDDEN);
        }

        DB::beginTransaction();
        try {
            DB::select('SELECT * FROM opinions FOR UPDATE ');
            $opinion->deleteOrFail();
            $opinionsCount = Opinion::where('topic_id', $topicId)->where('user_id', $userId)->count();
            if ($opinionsCount === 0) {
                DB::delete('DELETE FROM participant_topic WHERE participant_id = ? and topic_id = ?', [$userId, $topicId]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return $this->showMessage('의견 삭제');
    }

    public function update(Request $request, Opinion $opinion)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['user', 'content', 'title'], $input), '필드 확인');

        if ($input['user']->id !== $opinion->user_id) {
            return $this->showMessage('의견 수정 권리 없음', Response::HTTP_FORBIDDEN);
        }

        $opinion->content = $input['content'];
        $opinion->title = $input['title'];
        $opinion->saveOrFail();

        SummarizeJob::dispatch($input['content'], $opinion->id);

        return $this->showOne($opinion);
    }
}
