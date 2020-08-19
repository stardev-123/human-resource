<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UserBankAccountRequest extends FormRequest
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
                'bank_name' => 'required',
                'account_name' => 'required',
                'account_number' => 'required'
            ];
    }
    
    public function attributes()
    {
        return[
            'bank_name' => trans('messages.bank').' '.trans('messages.name'),
            'account_name' => trans('messages.account').' '.trans('messages.name'),
            'account_number' => trans('messages.account').' '.trans('messages.number'),
        ];
    }
}
