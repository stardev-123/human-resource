<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class PayrollRequest extends FormRequest
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
            'user_id' => 'required',
            'from_date' => 'required|date|before_or_equal:to_date',
            'to_date' => 'required|date',
            'date_of_payroll' => 'required|date|after_or_equal:to_date',
            'hourly' => 'required_if:type,hourly|numeric|min:0',
            'overtime' => 'required_if:type,monthly|numeric|min:0',
            'late' => 'required_if:type,monthly|numeric|min:0',
            'early_leaving' => 'required_if:type,monthly|numeric|min:0'
        ];
    }

    public function attributes()
    {
        return[
            'user_id' => trans('messages.user'),
            'from_date' => trans('messages.from').' '.trans('messages.date'),
            'to_date' => trans('messages.to').' '.trans('messages.date'),
            'date_of_payroll' => trans('messages.date_of').' '.trans('messages.payroll'),
            'hourly' => trans('messages.hourly').' '.trans('messages.salary'),
            'overtime' => trans('messages.overtime').' '.trans('messages.salary'),
            'late' => trans('messages.late').' '.trans('messages.salary'),
            'early_leaving' => trans('messages.early_leaving').' '.trans('messages.salary'),
        ];
    }
}
