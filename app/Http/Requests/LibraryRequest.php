<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class LibraryRequest extends FormRequest
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
        $library = $this->route('library');
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
                    'title' => 'required|unique:librarys,title',
                    'user_id' => 'array|required_if:audience2,user',
                    'designation_id' => 'array|required_if:audience2,designation'
                ];
                return $rules;
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'title' => 'required|unique:librarys,title,'.$library->id,
                    'user_id' => 'array|required_if:audience2,user',
                    'designation_id' => 'array|required_if:audience2,designation'
                ];
            }
            default:break;
        }
    }

    public function attributes()
    {
        return[
            'user_id' => trans('messages.user'),
            'designation_id' => trans('messages.designation'),
            'title' => trans('messages.title')
        ];
    }
}
