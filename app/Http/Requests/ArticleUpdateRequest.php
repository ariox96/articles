<?php

namespace App\Http\Requests;

use App\Enums\ArticleStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ArticleUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|min:1',
            'author_name' => 'required|string|min:3',
            'title' => 'required|string|min:10|max:191',
            'content' => 'required|string|min:3',
            'status' => [new Enum(ArticleStatusEnum::class)],
            'image' => 'file|max:5120|mimes:jpeg,png,jpg|nullable',
            'files.*' => [
                'file',
                'max:5120',
                Rule::notIn(['application/php', 'text/php']),
            ],
            'files' => 'max:5|nullable',
        ];
    }
}
