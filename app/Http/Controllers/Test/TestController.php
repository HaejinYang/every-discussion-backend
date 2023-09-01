<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\ApiController;

class TestController extends ApiController
{
    public function test()
    {
        return $this->showMessage('hello');
    }
}
