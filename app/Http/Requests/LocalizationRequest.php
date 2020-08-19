<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class LocalizationRequest extends FormRequest
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
        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'locale' => 'required',
                    'name' => 'required'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required'
                ];
            }
            default:break;
        }
    }

    public function attributes(){
        return [
            'name' => trans('messages.name'),
            'locale' => trans('messages.language')
        ];
    }
}
