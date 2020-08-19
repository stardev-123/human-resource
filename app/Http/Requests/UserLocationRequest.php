<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UserLocationRequest extends FormRequest
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
                'location_id' => 'required',
                'from_date' => 'required|date',
                'to_date' => 'date|after:from_date'
            ];
    }

    public function attributes(){
        return [
            'location_id' => trans('messages.location'),
            'from_date' => trans('messages.from').' '.trans('messages.date'),
            'to_date' => trans('messages.to').' '.trans('messages.date')
        ];
    }
}
