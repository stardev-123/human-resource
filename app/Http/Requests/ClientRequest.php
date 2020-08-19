<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
        $client = $this->route('client');
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
                  'first_name' => 'required|max:255',
                  'last_name' => 'required|max:255'
                ];
                return $rules;
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                  //'email'=>'email|unique:clients,'.$client->id,
                  'first_name' => 'required|max:255',
                  'last_name' => 'required|max:255'
                ];
            }
            default:break;
        }
    }

    public function attributes()
    {
        return[
            'email' => trans('messages.email'),
            'first_name' => trans('messages.first').' '.trans('messages.name'),
            'last_name' => trans('messages.last').' '.trans('messages.name'),
        ];
    }
}
