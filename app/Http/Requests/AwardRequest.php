<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class AwardRequest extends FormRequest
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
            'award_category_id' => 'required',
            'date_of_award' => 'required|date',
            'from_date' => 'required_if:duration,period|date|before_or_equal:to_date',
            'to_date' => 'required_if:duration,period|date',
            'month' => 'required_if:duration,monthly',
            'year' => 'required_if:duration,yearly',
            'user_id' => 'required|array'
        ];
    }

    public function attributes()
    {
        return[
            'award_category_id' => trans('messages.award').' '.trans('messages.category'),
            'date_of_award' => trans('messages.date_of').' '.trans('messages.award'),
            'from_date' => trans('messages.from').' '.trans('messages.date'),
            'to_date' => trans('messages.to').' '.trans('messages.date'),
            'month' => trans('messages.month'),
            'year' => trans('messages.year'),
            'user_id' => trans('messages.user')
        ];
    }
}
