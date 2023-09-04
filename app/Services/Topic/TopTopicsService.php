<?php

namespace App\Services\Topic;

use App\Models\Topic;

class TopTopicsService
{
    public static function getTopTopicsWithPagination(int $perPage)
    {
        return Topic::simplePaginate($perPage);
    }

    public static function getTopTopicsWithKeyword(string $keyword, int $perPage)
    {
        // whereFullText default mode : natural language mode
        // options 파라미터에 ['mode' => 'boolean']처럼 줘야 한다.
        return Topic::whereFullText('title', $keyword . "*", ['mode' => 'boolean'])->orderBy('title')->simplePaginate($perPage);
    }
}
