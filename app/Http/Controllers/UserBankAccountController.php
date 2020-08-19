<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\UserBankAccountRequest;
use Entrust;
use App\UserBankAccount;

Class UserBankAccountController extends Controller{
    use BasicController;

    protected $form = 'user-bank-account-form';

    public function accessible($user){
        if(!$user)
            return ['message' => trans('messages.invalid_link'),'status' => 'error'];

        $self_service = (config('config.user_manage_own_bank_account')) ? 1 : 0;

        if(!$this->userAccessible($user,$self_service))
            return ['message' => trans('messages.permission_denied'),'status' => 'error'];
        else
            return ['status' => 'success'];
    }

    public function lists(Request $request){

        $user = \App\User::find($request->input('id'));

        $accessible = $this->accessible($user);

        if($accessible['status'] != 'success')
            return;

        return view('user_bank_account.list',compact('user'))->render();
    }

    public function toggleLock(Request $request){
        $user_bank_account = UserBankAccount::find($request->input('id'));

        if(!$user_bank_account)
            return response()->json(['message' => trans('messages.invalid_link'), 'status' => 'error']);

        $user = $user_bank_account->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(($user_bank_account->user_id == \Auth::user()->id && count(getParent())) || ($user_bank_account->user_id != \Auth::user()->id && !Entrust::can('edit-user')))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $user_bank_account->is_locked = ($user_bank_account->is_locked) ? 0 : 1;
        $user_bank_account->save();

        return response()->json(['message' => '', 'status' => 'success']);
    }

    public function show(UserBankAccount $user_bank_account){
        $user = $user_bank_account->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        $custom_fields = \App\CustomField::whereForm($this->form)->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        return view('user_bank_account.show',compact('user','user_bank_account','values','col_ids','custom_fields'));
    }

	public function store(UserBankAccountRequest $request,$user_id){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = \App\User::find($user_id);

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserBankAccount::whereUserId($user->id)->whereAccountNumber($request->input('account_number'))->count())
            return response()->json(['message' => trans('messages.validation_unique',['attribute' => trans('messages.account').' '.trans('messages.number')]),'status' => 'error']);

        $user_bank_account = new UserBankAccount;
        $data = $request->all();
        $data['is_primary'] = ($request->has('is_primary')) ? 1 : 0;
	    $user_bank_account->fill($data);
        $user->userBankAccount()->save($user_bank_account);
        storeCustomField($this->form,$user_bank_account->id, $data);

        if($request->input('is_primary'))
            \App\UserBankAccount::where('user_id', $user_id)->where('id','!=',$user_bank_account->id)
                ->update(['is_primary' => 0]);

        $this->logActivity(['module' => 'user','module_id' => $user->id,'activity' => 'added','sub_module' => 'bank_account', 'sub_module_id' => $user_bank_account->id]);

        return response()->json(['message' => trans('messages.account').' '.trans('messages.added'), 'status' => 'success']);
	}

    public function edit(UserBankAccount $user_bank_account){
        $user = $user_bank_account->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        if($user_bank_account->is_locked && $user_bank_account->user_id == \Auth::user()->id && count(getParent()))
            return view('global.error',['message' => trans('messages.permission_denied')]);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $custom_user_bank_account_field_values = getCustomFieldValues($this->form,$user_bank_account->id);

        return view('user_bank_account.edit',compact('user_bank_account','custom_user_bank_account_field_values'));
    }

    public function update(UserBankAccountRequest $request, UserBankAccount $user_bank_account){
        
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = $user_bank_account->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if($user_bank_account->is_locked && $user_bank_account->user_id == \Auth::user()->id)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserBankAccount::where('id','!=',$user_bank_account->id)->whereUserId($user->id)->whereAccountNumber($request->input('account_number'))->count())
            return response()->json(['message' => trans('messages.validation_unique',['attribute' => trans('messages.account').' '.trans('messages.number')]),'status' => 'error']);

        $data = $request->all();
        $data['is_primary'] = ($request->has('is_primary')) ? 1 : 0;
        $user_bank_account->fill($data)->save();
        updateCustomField($this->form,$user_bank_account->id, $data);

        if($request->has('is_primary'))
            \App\UserBankAccount::where('user_id', $user->id)->where('id','!=',$user_bank_account->id)
                ->update(['is_primary' => 0]);

        $this->logActivity(['module' => 'user','module_id' => $user->id,'activity' => 'updated','sub_module' => 'bank_account', 'sub_module_id' => $user_bank_account->id]);

        return response()->json(['message' => trans('messages.account').' '.trans('messages.updated'), 'status' => 'success']);
    }

    public function destroy(Request $request, UserBankAccount $user_bank_account){
        $user = $user_bank_account->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if($user_bank_account->is_locked && $user_bank_account->user_id == \Auth::user()->id && count(getParent()))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if($user_bank_account->is_primary)
            return response()->json(['message' => trans('messages.primary_account_cannot_delete'), 'status' => 'error']);

        $this->logActivity(['module' => 'user','module_id' => $user->id,'activity' => 'deleted','sub_module' => 'bank_account', 'sub_module_id' => $user_bank_account->id]);

        deleteCustomField($this->form, $user_bank_account->id);
        $user_bank_account->delete();

        return response()->json(['message' => trans('messages.account').' '.trans('messages.deleted'), 'status' => 'success']);
    }
}