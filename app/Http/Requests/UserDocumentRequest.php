<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UserDocumentRequest extends FormRequest
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
                'document_type_id' => 'required',
                'date_of_expiry' => 'required|date',
                'title' => 'required'
            ];
    }

    public function attributes(){
        return [
            'document_type_id' => trans('messages.document').' '.trans('messages.type'),
            'date_of_expiry' => trans('messages.date_of').' '.trans('messages.expiry'),
            'title' => trans('messages.title')
        ];
    }
}
