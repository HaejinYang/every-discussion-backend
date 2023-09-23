<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\UserTopic\UserTopicShowRequest;
use App\Models\Participant;

class UserTopicController extends ApiController
{
    public function __invoke(Participant $user)
    {
        assert(!is_null($user), '유저 필드가 필요합니다.');

        return $this->showAll($user->topics()->get());
    }
}
