<?php

namespace App\Http\Requests\Opinion;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOpinionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'topicId' => ['required'],
            'title' => ['required', 'min:1'],
            'content' => ['required', 'min:1'],
            'agreeingType' => ['required', 'min:1'],
        ];
    }
}
