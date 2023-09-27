<?php

namespace App\Http\Requests\Test;

use App\Http\Requests\ExcludeRequest;

class TestRequest extends ExcludeRequest
{
    public function rules(): array
    {
        return [
            'email' => ['email', 'min:2']
        ];
    }
}
