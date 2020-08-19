<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UserSalaryRequest extends FormRequest
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
            'to_date' => 'required|date|after:from_date',
            'type' => 'required',
            'currency_id' => 'required',
            'hourly_rate' => 'required_if:type,hourly|numeric|min:0',
            'overtime_hourly_rate' => 'required_if:type,monthly|numeric|min:0',
            'late_hourly_rate' => 'required_if:type,monthly|numeric|min:0',
            'early_leaving_hourly_rate' => 'required_if:type,monthly|numeric|min:0'
        ];
    }

    public function attributes()
    {
        return[
            'from_date' => trans('messages.from').' '.trans('messages.date'),
            'to_date' => trans('messages.to').' '.trans('messages.date'),
            'type' => trans('messages.type'),
            'currency_id' => trans('messages.currency'),
            'hourly_rate' => trans('messages.hourly_rate'),
            'overtime_hourly_rate' => trans('messages.overtime').' '.trans('messages.hourly_rate'),
            'late_hourly_rate' => trans('messages.late').' '.trans('messages.hourly_rate'),
            'early_leaving_hourly_rate' => trans('messages.early_leaving').' '.trans('messages.hourly_rate')
        ];

    }
}