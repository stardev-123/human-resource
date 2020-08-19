<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class EducationLevelRequest extends FormRequest
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
        $education_level = $this->route('education_level');
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
                    'name' => 'required|unique:education_levels,name'
                ];
                return $rules;
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required|unique:education_levels,name,'.$education_level->id
                ];
            }
            default:break;
        }
    }

    public function attributes(){
        return [
            'name' => trans('messages.name')
        ];
    }
}
