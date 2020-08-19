<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class LeaveRequest extends FormRequest
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
            'leave_type_id' => 'required',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date'
        ];
    }

    public function attributes()
    {
        return[
            'leave_type_id' => trans('messages.leave').' '.trans('messages.type'),
            'from_date' => trans('messages.from').' '.trans('messages.date'),
            'to_date' => trans('messages.to').' '.trans('messages.date')
        ];

    }
}
