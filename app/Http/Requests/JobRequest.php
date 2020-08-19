<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
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
            'title' => 'required',
            'gender' => 'required|array',
            'contract_type_id' => 'required',
            'date_of_closing' => 'required|date',
            'no_of_post' => 'numeric|required',
            'designation_id' => 'required',
            'start_age' => 'required_if:age_info,1|numeric',
            'end_age' => 'required_if:age_info,1|numeric|greater_than_equal_to:start_age',
            'currency_id' => 'required_if:salary_info,1',
            'start_salary' => 'required_if:salary_info,1|numeric',
            'end_salary' => 'required_if:salary_info,1|numeric|greater_than_equal_to:start_salary'
        ];
    }

    public function attributes(){
        return [
            'title' => trans('messages.title'),
            'gender' => trans('messages.gender'),
            'contract_type_id' => trans('messages.contract').' '.trans('messages.type'),
            'date_of_closing' => trans('messages.date_of').' '.trans('messages.closing'),
            'no_of_post' => trans('messages.no_of').' '.trans('messages.post'),
            'designation_id' => trans('messages.designation'),
            'start_age' => trans('messages.start').' '.trans('messages.age'),
            'end_age' => trans('messages.end').' '.trans('messages.age'),
            'currency_id' => trans('messages.currency'),
            'start_salary' => trans('messages.start').' '.trans('messages.salary'),
            'end_salary' => trans('messages.end').' '.trans('messages.salary')
        ];
    }
}
