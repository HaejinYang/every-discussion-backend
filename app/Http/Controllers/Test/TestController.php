<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Test\TestRequest;

class TestController extends ApiController
{
    public function __invoke(TestRequest $request)
    {
        $result = $request->input();
        return $this->showAll($result);
    }
}
