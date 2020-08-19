<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\UserSalaryRequest;
use Entrust;
use App\UserSalary;

Class UserSalaryController extends Controller{
    use BasicController;

    protected $form = 'user-salary-form';

    public function accessible($user){
        if(!$user)
            return ['message' => trans('messages.invalid_link'),'status' => 'error'];

        if(!$this->userAccessible($user))
            return ['message' => trans('messages.permission_denied'),'status' => 'error'];
        else
            return ['status' => 'success'];
    }

    public function lists(Request $request){
        $user = \App\User::find($request->input('id'));

        $accessible = $this->accessible($user);

        if($accessible['status'] != 'success')
            return;

        $salary_heads = \App\SalaryHead::all();
        $earning_salary_heads = \App\SalaryHead::whereType('earning')->get();
        $deduction_salary_heads = \App\SalaryHead::whereType('deduction')->get();

        return view('user_salary.list',compact('user','salary_heads','earning_salary_heads','deduction_salary_heads'))->render();
    }

    public function edit(UserSalary $user_salary){
        $user = $user_salary->User;

        $accessible = $this->accessible($user);

        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        if(!Entrust::can('edit-user'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $salary_heads = \App\SalaryHead::all();
        $custom_user_salary_field_values = getCustomFieldValues($this->form,$user_salary->id);
        $currencies = \App\Currency::all()->pluck('name','id')->all();
        $earning_salary_heads = \App\SalaryHead::whereType('earning')->get();
        $deduction_salary_heads = \App\SalaryHead::whereType('deduction')->get();

        $salary = array();
        foreach($user_salary->UserSalaryDetail as $salary_detail)
            $salary[$salary_detail->salary_head_id] = currency($salary_detail->amount);

        return view('user_salary.edit',compact('user_salary','custom_user_salary_field_values','salary_heads','currencies','earning_salary_heads','deduction_salary_heads','salary'));
    }

    public function show(UserSalary $user_salary){
        $user = $user_salary->User;

        $accessible = $this->accessible($user);

        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        $custom_fields = \App\CustomField::whereForm($this->form)->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        return view('user_salary.show',compact('user_salary','custom_fields','col_ids','values'));
    }

    public function store(UserSalaryRequest $request, $user_id){
        $validation = validateCustomField($this->form,$request);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = \App\User::find($user_id);

        $accessible = $this->accessible($user);

        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $previous_record = UserSalary::whereUserId($user_id)->first();

        if($previous_record && $request->input('from_date') <= $previous_record->from_date)
            return response()->json(['message' => trans('messages.back_date_entry'), 'status' => 'error']);

        $salary = UserSalary::whereUserId($user_id)
            ->where(function ($query) use($request) { $query->where(function ($query) use($request){
                $query->where('from_date','<=',$request->input('from_date'))
                ->where('to_date','>=',$request->input('from_date'));
                })->orWhere(function ($query) use($request) {
                    $query->where('from_date','<=',$request->input('to_date'))
                        ->where('to_date','>=',$request->input('to_date'));
                });})->count();

        if($salary)
            return response()->json(['message' => trans('messages.entry_already_defined'), 'status' => 'error']);

        $user_salary = new UserSalary;
        $data = $request->all();
        $user_salary->fill($data);
        $user_salary->hourly_rate = ($request->input('type') == 'hourly') ? $request->input('hourly_rate') : 0;
        $user_salary->overtime_hourly_rate = ($request->input('type') == 'monthly') ? $request->input('overtime_hourly_rate') : 0;
        $user_salary->late_hourly_rate = ($request->input('type') == 'monthly') ? $request->input('late_hourly_rate') : 0;
        $user_salary->early_leaving_hourly_rate = ($request->input('type') == 'monthly') ? $request->input('early_leaving_hourly_rate') : 0;
        $user_salary->save();
        $user->userSalary()->save($user_salary);

        $salary_heads = $request->input('salary_head');
        foreach($salary_heads as $key => $value){
            $user_salary_detail = new \App\UserSalaryDetail;
            $user_salary_detail->salary_head_id = $key;
            $user_salary_detail->amount = $value;
            $user_salary_detail->user_salary_id = $user_salary->id;
            $user_salary_detail->save();
        }
        storeCustomField($this->form,$user_salary->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user_id, 'activity' => 'added','sub_module' => 'salary','sub_module_id' => $user_salary->id]);

        return response()->json(['message' => trans('messages.salary').' '.trans('messages.added'), 'status' => 'success']);
    }

    public function update(UserSalaryRequest $request, UserSalary $user_salary){
        $validation = validateCustomField($this->form,$request);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = $user_salary->User;

        $accessible = $this->accessible($user);

        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $previous_record = UserSalary::whereUserId($user_salary->user_id)->where('id','!=',$user_salary->id)->first();

        if($previous_record && $request->input('from_date') <= $previous_record->from_date)
            return response()->json(['message' => trans('messages.back_date_entry'), 'status' => 'error','previous_record' => $previous_record]);

        $salary = UserSalary::whereUserId($user_salary->user_id)->where('id','!=',$user_salary->id)
            ->where(function ($query) use($request) { $query->where(function ($query) use($request){
                $query->where('from_date','<=',$request->input('from_date'))
                ->where('to_date','>=',$request->input('from_date'));
                })->orWhere(function ($query) use($request) {
                    $query->where('from_date','<=',$request->input('to_date'))
                        ->where('to_date','>=',$request->input('to_date'));
                });})->count();

        if($salary)
            return response()->json(['message' => trans('messages.entry_already_defined'), 'status' => 'error']);

        $data = $request->all();
        $user_salary->fill($data);
        $user_salary->hourly_rate = ($request->input('type') == 'hourly') ? $request->input('hourly_rate') : 0;
        $user_salary->overtime_hourly_rate = ($request->input('type') == 'monthly') ? $request->input('overtime_hourly_rate') : 0;
        $user_salary->late_hourly_rate = ($request->input('type') == 'monthly') ? $request->input('late_hourly_rate') : 0;
        $user_salary->early_leaving_hourly_rate = ($request->input('type') == 'monthly') ? $request->input('early_leaving_hourly_rate') : 0;
        $user_salary->save();

        if($request->input('type') == 'hourly')
            \App\UserSalaryDetail::whereUserSalaryId($user_salary->id)->delete();
        else {
            $salary_heads = $request->input('salary_head');
            foreach($salary_heads as $key => $value){
                $user_salary_detail = \App\UserSalaryDetail::firstOrNew(['user_salary_id' => $user_salary->id, 'salary_head_id' => $key]);
                $user_salary_detail->amount = $value;
                $user_salary_detail->save();
            }
        }
        updateCustomField($this->form,$user_salary->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user->id, 'activity' => 'updated','sub_module' => 'salary','sub_module_id' => $user_salary->id]);

        return response()->json(['message' => trans('messages.salary').' '.trans('messages.updated'), 'status' => 'success']);
    }

    public function destroy(Request $request, UserSalary $user_salary){
        $user = $user_salary->User;

        $accessible = $this->accessible($user);

        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        deleteCustomField($this->form, $user_salary->id);

        $this->logActivity(['module' => 'user','module_id' => $user->id, 'activity' => 'deleted','sub_module' => 'salary','sub_module_id' => $user_salary->id]);

        $user_salary->delete();

        return response()->json(['message' => trans('messages.salary').' '.trans('messages.deleted'), 'status' => 'success']);
    }

}
