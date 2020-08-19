<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\UserContractRequest;
use Entrust;
use App\UserContract;

Class UserContractController extends Controller{
    use BasicController;

    protected $form = 'user-contract-form';

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

        return view('user_contract.list',compact('user'))->render();
    }

    public function show(UserContract $user_contract){
        $user = $user_contract->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        $custom_fields = \App\CustomField::whereForm($this->form)->get();
        $uploads = \App\Upload::whereModule('user-contract')->whereModuleId($user_contract->id)->whereStatus(1)->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        return view('user_contract.show',compact('user','user_contract','values','col_ids','custom_fields','uploads'));
    }

    public function edit(UserContract $user_contract){
        $user = $user_contract->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        if(!Entrust::can('edit-user'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $contract_types = \App\ContractType::all()->pluck('name','id')->all();
        $custom_user_contract_field_values = getCustomFieldValues($this->form,$user_contract->id);
        
        $uploads = editUpload('user-contract',$user_contract->id);
        return view('user_contract.edit',compact('user_contract','custom_user_contract_field_values','contract_types','uploads'));
    }

    public function store(UserContractRequest $request, $user_id){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = \App\User::find($user_id);

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserContract::whereUserId($user_id)->whereNull('to_date')->count())
            return response()->json(['message' => trans('messages.already_undefined_end_date'), 'status' => 'error']);

        $previous_record = UserContract::whereUserId($user_id)->first();

        if($previous_record && $request->input('from_date') <= $previous_record->from_date)
            return response()->json(['message' => trans('messages.back_date_entry'), 'status' => 'error']);

        if($request->has('to_date'))
            $contract = UserContract::whereUserId($user_id)
                ->where(function ($query) use($request) { $query->where(function ($query) use($request){
                    $query->where('from_date','<=',$request->input('from_date'))
                    ->where('to_date','>=',$request->input('from_date'));
                    })->orWhere(function ($query) use($request) {
                        $query->where('from_date','<=',$request->input('to_date'))
                            ->where('to_date','>=',$request->input('to_date'));
                    });})->count();
        else
            $contract = UserContract::whereUserId($user_id)->where('from_date','<=',$request->input('from_date'))->where('to_date','>=',$request->input('from_date'))->count();

        if($contract)
            return response()->json(['message' => trans('messages.entry_already_defined'), 'status' => 'error']);

        $upload_validation = validateUpload('user-contract',$request);

        if($upload_validation['status'] == 'error')
            return response()->json($upload_validation);

        $user_contract = new UserContract;
        $data = $request->all();
        $data['to_date'] = ($request->has('to_date')) ? $request->input('to_date') : null;
        $user_contract->fill($data)->save();
        $user->userContract()->save($user_contract);
        storeUpload('user-contract',$user_contract->id,$request);
        storeCustomField($this->form,$user_contract->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user_id, 'activity' => 'added','sub_module' => 'contract','sub_module_id' => $user_contract->id]);

        return response()->json(['message' => trans('messages.contract').' '.trans('messages.added'), 'status' => 'success']);
    }

    public function update(UserContractRequest $request, UserContract $user_contract){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = $user_contract->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserContract::whereUserId($user_contract->user_id)->where('id','!=',$user_contract->id)->whereNull('to_date')->count())
            return response()->json(['message' => trans('messages.already_undefined_end_date'), 'status' => 'error']);

        $previous_record = UserContract::whereUserId($user_contract->user_id)->where('id','!=',$user_contract->id)->first();

        if($previous_record && $request->input('from_date') <= $previous_record->from_date)
            return response()->json(['message' => trans('messages.back_date_entry'), 'status' => 'error']);

        if($request->has('to_date'))
            $contract = UserContract::whereUserId($user_contract->user_id)->where('id','!=',$user_contract->id)
                ->where(function ($query) use($request) { $query->where(function ($query) use($request){
                    $query->where('from_date','<=',$request->input('from_date'))
                    ->where('to_date','>=',$request->input('from_date'));
                    })->orWhere(function ($query) use($request) {
                        $query->where('from_date','<=',$request->input('to_date'))
                            ->where('to_date','>=',$request->input('to_date'));
                    });})->count();
        else
            $contract = UserContract::whereUserId($user_contract->user_id)->where('id','!=',$user_contract->id)->where('from_date','<=',$request->input('from_date'))
                        ->where('to_date','>=',$request->input('from_date'))->count();

        if($contract)
            return response()->json(['message' => trans('messages.entry_already_defined'), 'status' => 'error']);

        $upload_validation = updateUpload('user-contract',$user_contract->id,$request);

        if($upload_validation['status'] == 'error')
            return response()->json($upload_validation);

        $data = $request->all();
        $data['to_date'] = ($request->has('to_date')) ? $request->input('to_date') : null;
        $user_contract->fill($data)->save();
        updateCustomField($this->form,$user_contract->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user->id, 'activity' => 'updated','sub_module' => 'contract','sub_module_id' => $user_contract->id]);

        return response()->json(['message' => trans('messages.contract').' '.trans('messages.updated'), 'status' => 'success']);
    }

    public function download($id){
        $upload = \App\Upload::whereUuid($id)->whereModule('user-contract')->whereStatus(1)->first();

        if(!$upload)
            return redirect('/user')->withErrors(trans('messages.invalid_link'));

        $user_contract = UserContract::find($upload->module_id);

        if(!$user_contract)
            return redirect('/user')->withErrors(trans('messages.invalid_link'));

        $user = $user_contract->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return redirect('/user/'.$user->id)->withErrors(trans('messages.permission_denied'));

        if(!\Storage::exists('attachments/'.$upload->attachments))
            return redirect('/user/'.$user->id)->withErrors(trans('messages.file_not_found'));

        $download_path = storage_path().config('constant.storage_root').'attachments/'.$upload->attachments;

        return response()->download($download_path, $upload->user_filename);
    }

    public function destroy(Request $request, UserContract $user_contract){
        $user = $user_contract->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        deleteUpload('user-contract',$user_contract->id);
        deleteCustomField($this->form, $user_contract->id);

        $this->logActivity(['module' => 'user','module_id' => $user->id, 'activity' => 'deleted','sub_module' => 'contract','sub_module_id' => $user_contract->id]);

        $user_contract->delete();

        return response()->json(['message' => trans('messages.contract').' '.trans('messages.deleted'), 'status' => 'success']);
    }

}