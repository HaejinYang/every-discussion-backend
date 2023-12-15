<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\ApiController;
use App\Models\Opinion;
use App\Models\Topic;
use App\Services\Topic\TopicService;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopicOpinionController extends ApiController
{
    public function index(Request $request, Topic $topic): JsonResponse
    {
        $keyword = $request->input('keyword');
        $topicService = new TopicService($topic);

        if (is_null($keyword)) {
            return $this->showAll($topicService->opinions());
        }

        return $this->showAll($topicService->searchOpinions($keyword));
    }

    public function graph(Request $request, int $id): JsonResponse
    {
        if (!is_numeric($id)) {
            return $this->error('올바른 파라미터가 아닙니다.');
        }

        //$result = DB::select("SELECT r.refer_to_id, count(r.refer_to_id) from opinions as o, opinions_reference as r where topic_id = :id and r.opinion_id = o.id group by r.refer_to_id having count(r.refer_to_id) > 0 order by count(r.refer_to_id) desc limit 10", ['id' => $id]);
        $result = DB::table('opinions')
            ->join('opinions_reference', 'opinions.id', '=', 'opinions_reference.opinion_id')
            ->where('opinions.topic_id', '=', $id)
            ->groupBy('opinions_reference.refer_to_id')
            ->havingRaw('count(opinions_reference.refer_to_id)')
            ->orderByRaw('count(opinions_reference.refer_to_id) DESC')
            ->Limit(10)
            ->selectRaw('opinions_reference.refer_to_id, count(opinions_reference.refer_to_id) as count')
            ->get();

        foreach ($result as $relation) {
            $referToOpinion = Opinion::where('id', $relation->refer_to_id)->get();
            $opinions = Opinion::whereIn('id', function (Builder $query) use ($relation) {
                $query->select('opinion_id')
                    ->from('opinions_reference')
                    ->where('refer_to_id', $relation->refer_to_id)
                    ->get();
            })->get();

            $relation->opinions = array_map(function ($item) {
                return (object)['id' => $item['id'], 'title' => $item['title'], 'agree_type' => $item['agree_type']];
            }, $opinions->toArray());

            $relation->title = $referToOpinion->pluck('title')->first();
            $relation->agree_type = $referToOpinion->pluck('agree_type')->first();
        }

        return $this->showAll($result);
    }
}
