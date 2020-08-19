<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class TaskCategoryRequest extends FormRequest
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
        $task_category = $this->route('task_category');
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
                    'name' => 'required|unique:task_categories,name'
                ];
                return $rules;
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required|unique:task_categories,name,'.$task_category->id
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
