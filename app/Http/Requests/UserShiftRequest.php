<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UserShiftRequest extends FormRequest
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
            'from_date' => 'required|date',
            'to_date' => 'date|after:from_date',
            'shift_type' => 'required',
            'shift_id' => 'required_if:shift_type,predefined',
            'in_time' => 'required_if:shift_type,custom',
            'out_time' => 'required_if:shift_type,custom'
        ];
    }

    public function attributes()
    {
        return[
            'in_time' => trans('messages.in_time'),
            'out_time' => trans('messages.out_time'),
            'shift_id' => trans('messages.shift'),
            'from_date' => trans('messages.from').' '.trans('messages.date'),
            'to_date' => trans('messages.to').' '.trans('messages.date'),
            'shift_type' => trans('messages.shift').' '.trans('messages.type')
        ];

    }
}