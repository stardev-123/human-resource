<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\UserContactRequest;
use Entrust;
use App\UserContact;

Class UserContactController extends Controller{
    use BasicController;

    protected $form = 'user-contact-form';

    public function accessible($user){
        if(!$user)
            return ['message' => trans('messages.invalid_link'),'status' => 'error'];

        $self_service = (config('config.user_manage_own_contact')) ? 1 : 0;

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

        return view('user_contact.list',compact('user'))->render();
    }

    public function toggleLock(Request $request){
        $user_contact = UserContact::find($request->input('id'));

        if(!$user_contact)
            return response()->json(['message' => trans('messages.invalid_link'), 'status' => 'error']);

        $user = $user_contact->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(($user_contact->user_id == \Auth::user()->id && count(getParent())) || ($user_contact->user_id != \Auth::user()->id && !Entrust::can('edit-user')))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $user_contact->is_locked = ($user_contact->is_locked) ? 0 : 1;
        $user_contact->save();

        return response()->json(['message' => '', 'status' => 'success']);
    }

    public function show(UserContact $user_contact){
        $user = $user_contact->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        $custom_fields = \App\CustomField::whereForm($this->form)->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        return view('user_contact.show',compact('user','user_contact','values','col_ids','custom_fields'));
    }

	public function store(UserContactRequest $request,$user_id){

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = \App\User::find($user_id);

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserContact::whereUserId($user->id)->whereName($request->input('name'))->count())
            return response()->json(['message' => trans('messages.validation_unique',['attribute' => trans('messages.name')]),'status' => 'error']);

        $user_contact = new UserContact;
        $data = $request->all();
        $data['is_dependent'] = ($request->has('is_dependent')) ? 1 : 0;
        $data['is_primary'] = ($request->has('is_primary')) ? 1 : 0;
	    $user_contact->fill($data);
        $user->userContact()->save($user_contact);
        storeCustomField($this->form,$user_contact->id, $data);

        if($request->input('is_primary'))
            \App\UserContact::where('user_id', $user_id)->where('id','!=',$user_contact->id)
                ->update(['is_primary' => 0]);

        $this->logActivity(['module' => 'user','module_id' => $user->id,'activity' => 'added','sub_module' => 'contact', 'sub_module_id' => $user->id]);

        return response()->json(['message' => trans('messages.contact').' '.trans('messages.added'), 'status' => 'success']);
	}

    public function edit(UserContact $user_contact){
        $user = $user_contact->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        if($user_contact->is_locked && $user_contact->user_id == \Auth::user()->id && count(getParent()))
            return view('global.error',['message' => trans('messages.permission_denied')]);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $user_relation = translateList('user_relation');
        $custom_user_contact_field_values = getCustomFieldValues($this->form,$user_contact->id);

        return view('user_contact.edit',compact('user_contact','user_relation','custom_user_contact_field_values'));
    }

    public function update(UserContactRequest $request, UserContact $user_contact){

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = $user_contact->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if($user_contact->is_locked && $user_contact->user_id == \Auth::user()->id)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserContact::where('id','!=',$user_contact->id)->whereUserId($user->id)->whereName($request->input('name'))->count())
            return response()->json(['message' => trans('messages.validation_unique',['attribute' => trans('messages.name')]),'status' => 'error']);

        $data = $request->all();
        $data['is_dependent'] = ($request->has('is_dependent')) ? 1 : 0;
        $data['is_primary'] = ($request->has('is_primary')) ? 1 : 0;
        $user_contact->fill($data)->save();
        updateCustomField($this->form,$user_contact->id, $data);

        if($request->has('is_primary'))
            \App\UserContact::where('user_id', $user->id)->where('id','!=',$user_contact->id)
                ->update(['is_primary' => 0]);

        $this->logActivity(['module' => 'user','module_id' => $user->id,'activity' => 'updated','sub_module' => 'contact', 'sub_module_id' => $user_contact->id]);

        return response()->json(['message' => trans('messages.contact').' '.trans('messages.updated'), 'status' => 'success']);
    }

    public function destroy(Request $request, UserContact $user_contact){
        $user = $user_contact->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if($user_contact->is_locked && $user_contact->user_id == \Auth::user()->id && count(getParent()))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if($user_contact->is_primary)
            return response()->json(['message' => trans('messages.primary_contact_cannot_delete'), 'status' => 'error']);

        $this->logActivity(['module' => 'user','module_id' => $user->id,'activity' => 'deleted','sub_module' => 'contact', 'sub_module_id' => $user_contact->id]);

        deleteCustomField($this->form, $user_contact->id);
        $user_contact->delete();

        return response()->json(['message' => trans('messages.contact').' '.trans('messages.deleted'), 'status' => 'success']);
    }
}