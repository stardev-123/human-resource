<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
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
        $location = $this->route('location');
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
                    'name' => 'required|unique:locations',
                    'city' => 'required',
                    'state' => 'required',
                    'country_id' => 'required'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required|unique:locations,name,'.$location->id,
                    'city' => 'required',
                    'state' => 'required',
                    'country_id' => 'required'
                ];
            }
            default:break;
        }
    }

    public function attributes(){
        return [
            'name' => trans('messages.name'),
            'city' => trans('messages.city'),
            'state' => trans('messages.state'),
            'country_id' => trans('messages.country')
        ];
    }
}
