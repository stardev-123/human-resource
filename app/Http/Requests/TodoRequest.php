<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class TodoRequest extends FormRequest
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
        $todo = $this->route('todo');
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
                    'title' => 'required|unique_with:todos,user_id,date',
                    'date' => 'required|date'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'date' => 'required|date',
                    'title' => 'required|unique_with:todos,user_id,date,'.$todo->id
                ];
            }
            default:break;
        }
    }

    public function attributes(){
        return [
            'date' => trans('messages.date'),
            'title' => trans('messages.title')
        ];
    }
}
