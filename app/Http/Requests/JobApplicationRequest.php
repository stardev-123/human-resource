<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class JobApplicationRequest extends FormRequest
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
            'job_id' => 'required',
            'first_name' => 'sometimes|required',
            'email' => 'sometimes|required|email',
            'last_name' => 'sometimes|required',
            'primary_contact_number' => 'sometimes|required',
            'secondary_contact_number' => 'sometimes|required',
            'date_of_birth' => 'sometimes|required|date',
            'gender' => 'sometimes|required',
            'address_line_1' => 'sometimes|required',
            'city' => 'sometimes|required',
            'state' => 'sometimes|required',
            'zipcode' => 'sometimes|required',
            'country_id' => 'sometimes|required',
            'date_of_application' => 'sometimes|required|date',
            'source' => 'sometimes|required',
        ];
    }

    public function attributes()
    {
        return [
            'job_id' => trans('messages.job'),
            'first_name' => trans('messages.first').' '.trans('messages.name'),
            'email' => trans('messages.email'),
            'last_name' => trans('messages.last').' '.trans('messages.name'),
            'primary_contact_number' => trans('messages.primary').' '.trans('messages.contact').' '.trans('messages.number'),
            'secondary_contact_number' => trans('messages.secondary').' '.trans('messages.contact').' '.trans('messages.number'),
            'date_of_birth' => trans('messages.date_of').' '.trans('messages.birth'),
            'gender' => trans('messages.gender'),
            'address_line_1' => trans('messages.address_line_1'),
            'city' => trans('messages.city'),
            'state' => trans('messages.state'),
            'zipcode' => trans('messages.postcode'),
            'country_id' => trans('messages.country'),
            'date_of_application' => trans('messages.date_of').' '.trans('messages.application'),
            'source' => trans('messages.source')
        ];
    }
}
