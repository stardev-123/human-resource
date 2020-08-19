<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class CustomFieldRequest extends FormRequest
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
            'form' => 'required',
            'title' => 'required|unique_with:custom_fields,form',
            'type' => 'required',
            'options' => 'required_if:type,select|required_if:type,radio|required_if:type,checkbox'
        ];
    }

    public function attributes()
    {
        return [
            'form' => trans('messages.form'),
            'title' => trans('messages.title'),
            'type' => trans('messages.type'),
            'options' => trans('messages.options')
        ];
    }
}
