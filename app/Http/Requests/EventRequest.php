<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'image' => 'sometimes|nullable|mimes:jpeg,jpg,png|required|max:15000',
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|regex:/^\d*(\.\d{2})?$/',
            'starts' => 'required|date_format:Y-m-d H:i',
            'ends' => 'required|date_format:Y-m-d H:i|after:starts',
            'genre' => 'required',
            'address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'post_code' => 'required',
        ];
    }
}
