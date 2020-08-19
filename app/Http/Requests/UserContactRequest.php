<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UserContactRequest extends FormRequest
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
                'name' => 'required',
                'relation' => 'required',
                'work_email' => 'email',
                'personal_email' => 'email',
            ];
    }

    public function attributes()
    {
        return [
            'name' => trans('messages.name'),
            'relation' => trans('messages.relation'),
            'work_email' => trans('messages.work').' '.trans('messages.email'),
            'personal_email' => trans('messages.personal').' '.trans('messages.email')
        ];
    }
}
