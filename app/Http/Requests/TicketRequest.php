<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
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
            'ticket_category_id' => 'required',
            'subject' => 'required',
            'ticket_priority_id' => 'required'
        ];
    }

    public function attributes(){
        return [
            'ticket_category_id' => trans('messages.ticket').' '.trans('messages.category'),
            'subject' => trans('messages.subject'),
            'ticket_priority_id' => trans('messages.ticket').' '.trans('messages.priority')
        ];
    }
}
