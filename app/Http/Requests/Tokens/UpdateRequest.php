<?php

namespace App\Http\Requests\Tokens;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => 'sometimes|nullable',
            'image_url' => 'sometimes|nullable|required_with:thumb_url|url',
            'thumb_url' => 'sometimes|nullable|required_with:image_url|url',
        ];
    }
}
