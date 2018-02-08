<?php

namespace App\Http\Requests\Groups;

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
            'address' => 'required|exists:players,address',
            'name' => 'required|unique:groups,name|min:5|max:30',
            'description' => 'required|min:10|max:255',
            'timestamp' => 'required',
            'signature' => 'required',
        ];
    }
}
