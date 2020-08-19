<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class AnnouncementRequest extends FormRequest
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
        $announcement = $this->route('announcement');
        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                $rules = [
                    'title' => 'required|unique:announcements,title',
                    'from_date' => 'required|date|before_or_equal:to_date',
                    'to_date' => 'required|date',
                    'audience' => 'required',
                    'user_id' => 'array|required_if:audience,user',
                    'designation_id' => 'array|required_if:audience,designation'
                ];
                return $rules;
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'title' => 'required|unique:announcements,title,'.$announcement->id,
                    'from_date' => 'required|date|before_or_equal:to_date',
                    'to_date' => 'required|date',
                    'audience' => 'required',
                    'user_id' => 'array|required_if:audience,user',
                    'designation_id' => 'array|required_if:audience,designation'
                ];
            }
            default:break;
        }
    }

    public function attributes()
    {
        return[
            'user_id' => trans('messages.user'),
            'designation_id' => trans('messages.designation'),
            'from_date' => trans('messages.from').' '.trans('messages.date'),
            'to_date' => trans('messages.to').' '.trans('messages.date'),
            'audience' => trans('messages.audience'),
            'title' => trans('messages.title')
        ];
    }
}
