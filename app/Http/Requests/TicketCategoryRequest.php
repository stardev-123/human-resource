<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class TicketCategoryRequest extends FormRequest
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
        $ticket_category = $this->route('ticket_category');
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
                    'name' => 'required|unique:ticket_categories,name'
                ];
                return $rules;
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required|unique:ticket_categories,name,'.$ticket_category->id
                ];
            }
            default:break;
        }
    }
    
    public function attributes()
    {
        return[
            'name' => trans('messages.name')
        ];
    }
}
