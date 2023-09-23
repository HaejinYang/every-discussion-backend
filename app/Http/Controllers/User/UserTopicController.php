<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Models\Participant;

class UserTopicController extends ApiController
{
    public function __invoke(Participant $user)
    {
        return $this->showAll($user->topics()->get());
    }
}
