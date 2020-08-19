<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
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
        $currency = $this->route('currency');
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
                    'name' => 'required|unique:currencies,name',
                    'symbol' => 'required|unique:currencies,symbol',
                    'position' => 'required'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required|unique:currencies,name,'.$currency->id,
                    'symbol' => 'required|unique:currencies,symbol,'.$currency->id,
                    'position' => 'required'
                ];
            }
            default:break;
        }
    }

    public function attributes(){
        return [
            'name' => trans('messages.name'),
            'symbol' => trans('messages.symbol'),
            'position' => trans('messages.position')
        ];
    }
}
