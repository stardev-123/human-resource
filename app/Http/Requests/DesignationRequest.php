<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class DesignationRequest extends FormRequest
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
        $designation = $this->route('designation');
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
                    'name' => 'required|unique_with:designations,department_id',
                    'department_id' => 'required'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'department_id' => 'required',
                    'name' => 'required|unique_with:designations,department_id,'.$designation->id
                    
                ];
            }
            default:break;
        }
    }

    public function attributes()
    {
        return[
            'department_id' => trans('messages.department'),
            'name' => trans('messages.name')
        ];

    }
}
