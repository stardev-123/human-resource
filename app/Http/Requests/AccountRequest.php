<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
            'hostname' => 'required',
            'mysql_username' => 'required',
            'mysql_database' => 'required',
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            'password' => 'required',
            'envato_username' => 'required',
            'purchase_code' => 'required',
        ];
    }
}
