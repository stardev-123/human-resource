<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
        $task = $this->route('task');
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
                    'title' => 'required|unique:tasks',
                    'task_category_id' => 'required',
                    'task_priority_id' => 'required',
                    'start_date' => 'required|date|before_or_equal:due_date',
                    'due_date' => 'required|date',
                    'description' => 'required'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'title' => 'required|unique:tasks,title,'.$task->id.',id',
                    'task_category_id' => 'required',
                    'task_priority_id' => 'required',
                    'start_date' => 'required|date|before_or_equal:due_date',
                    'due_date' => 'required|date',
                    'description' => 'required'
                ];
            }
            default:break;
        }
    }

    public function attributes()
    {
        return[
            'title' => trans('messages.title'),
            'task_category_id' => trans('messages.task').' '.trans('messages.category'),
            'task_priority_id' => trans('messages.task').' '.trans('messages.priority'),
            'start_date' => trans('messages.start').' '.trans('messages.date'),
            'due_date' => trans('messages.due').' '.trans('messages.date'),
            'description' => trans('messages.description')
        ];
    }
}
