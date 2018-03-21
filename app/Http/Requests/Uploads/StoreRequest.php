<?php

namespace App\Http\Requests\Uploads;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'required|mimetypes:image/jpeg|mimes:jpeg,jpg|dimensions:width=1600,height=900|max:5000',
            'timestamp' => 'required',
            'signature' => 'required',
        ];
    }
}
