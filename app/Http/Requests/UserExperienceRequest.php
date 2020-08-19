<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UserExperienceRequest extends FormRequest
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
                'company_name' => 'required',
                'from_date' => 'required|date',
                'to_date' => 'required|date|after:from_date',
                'job_title' => 'required',
            ];
    }

    public function attributes(){
        return [
            'from_date' => trans('messages.from').' '.trans('messages.date'),
            'to_date' => trans('messages.to').' '.trans('messages.date'),
            'company_name' => trans('messages.company').' '.trans('messages.name'),
            'job_title' => trans('messages.job').' '.trans('messages.title'),
        ];
    }
}
