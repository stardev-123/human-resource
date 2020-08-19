<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UserEmploymentRequest extends FormRequest
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
                'date_of_joining' => 'required|date',
                'date_of_leaving' => 'date|after:date_of_joining'
            ];
    }

    public function attributes(){
        return [
            'date_of_joining' => trans('messages.date_of').' '.trans('messages.joining'),
            'date_of_leaving' => trans('messages.date_of').' '.trans('messages.leaving')
        ];
    }
}
