<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ExcludeRequest extends FormRequest
{
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $result = array_diff_key($validator->attributes(), $this->rules());
                if (!empty($result)) {
                    $validator->errors()->add(
                        'extra_key',
                        'extra key without specification'
                    );
                }
            }
        ];
    }
}
