<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
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
        return [
            'expense_head_id' => 'required',
            'date_of_expense' => 'required',
            'amount' => 'required|numeric|min:0'
        ];
    }

    public function attributes()
    {
        return[
            'expense_head_id' => trans('messages.expense').' '.trans('messages.head'),
            'date_of_expense' => trans('messages.date_of').' '.trans('messages.expense'),
            'amount' => trans('messages.amount')
        ];

    }
}
