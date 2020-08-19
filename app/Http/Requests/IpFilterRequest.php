<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class IpFilterRequest extends FormRequest
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
            'start' => 'required|ip',
            'end' => 'ip'
        ];
    }

    public function attributes(){
        return [
            'start' => trans('messages.start').' IP ',
            'end' => trans('messages.end').' IP ',
        ];
    }
}
