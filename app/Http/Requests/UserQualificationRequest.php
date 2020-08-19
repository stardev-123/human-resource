<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UserQualificationRequest extends FormRequest
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
                'institute_name' => 'required',
                'from_date' => 'required|date',
                'to_date' => 'required|date|after:from_date',
                'education_level_id' => 'required',
            ];
    }

    public function attributes(){
        return [
            'from_date' => trans('messages.from').' '.trans('messages.date'),
            'to_date' => trans('messages.to').' '.trans('messages.date'),
            'institute_name' => trans('messages.institute').' '.trans('messages.name'),
            'education_level_id' => trans('messages.education').' '.trans('messages.level')
        ];
    }
}
