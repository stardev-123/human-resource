<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\UserLocationRequest;
use Entrust;
use App\UserLocation;

Class UserLocationController extends Controller{
    use BasicController;

    protected $form = 'user-location-form';

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

        return view('user_location.list',compact('user'))->render();
    }

    public function show(UserLocation $user_location){
        $user = $user_location->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        $custom_fields = \App\CustomField::whereForm($this->form)->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        return view('user_location.show',compact('user','user_location','values','col_ids','custom_fields'));
    }

    public function edit(UserLocation $user_location){
        $user = $user_location->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        if(!Entrust::can('edit-user'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $locations = \App\Location::whereIn('id',getLocation())->get()->pluck('name','id')->all();
        $custom_user_location_field_values = getCustomFieldValues($this->form,$user_location->id);
        
        return view('user_location.edit',compact('user_location','custom_user_location_field_values','locations'));
    }

    public function store(UserLocationRequest $request, $user_id){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = \App\User::find($user_id);

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserLocation::whereUserId($user_id)->whereNull('to_date')->count())
            return response()->json(['message' => trans('messages.already_undefined_end_date'), 'status' => 'error']);

        $previous_record = UserLocation::whereUserId($user_id)->first();

        if($previous_record && $request->input('from_date') <= $previous_record->from_date)
            return response()->json(['message' => trans('messages.back_date_entry'), 'status' => 'error']);

        if($request->has('to_date'))
            $location = UserLocation::whereUserId($user_id)
                ->where(function ($query) use($request) { $query->where(function ($query) use($request){
                    $query->where('from_date','<=',$request->input('from_date'))
                    ->where('to_date','>=',$request->input('from_date'));
                    })->orWhere(function ($query) use($request) {
                        $query->where('from_date','<=',$request->input('to_date'))
                            ->where('to_date','>=',$request->input('to_date'));
                    });})->count();
        else
            $location = UserLocation::whereUserId($user_id)->where('from_date','<=',$request->input('from_date'))->where('to_date','>=',$request->input('from_date'))->count();

        if($location)
            return response()->json(['message' => trans('messages.entry_already_defined'), 'status' => 'error']);

        $user_location = new UserLocation;
        $data = $request->all();
        $data['to_date'] = ($request->has('to_date')) ? $request->input('to_date') : null;
        $user_location->fill($data)->save();
        $user->userLocation()->save($user_location);

        $profile = $user->Profile;
        $current_location_id = getUserLocation(date('Y-m-d'),$user_id);
        $profile->location_id = ($current_location_id) ? : null;
        $profile->save();
        storeCustomField($this->form,$user_location->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user_id, 'activity' => 'added','sub_module' => 'location','sub_module_id' => $user_location->id]);

        return response()->json(['message' => trans('messages.location').' '.trans('messages.added'), 'status' => 'success']);
    }

    public function update(UserLocationRequest $request, UserLocation $user_location){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = $user_location->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserLocation::whereUserId($user_location->user_id)->where('id','!=',$user_location->id)->whereNull('to_date')->count())
            return response()->json(['message' => trans('messages.already_undefined_end_date'), 'status' => 'error']);

        $previous_record = UserLocation::whereUserId($user_location->user_id)->where('id','!=',$user_location->id)->first();

        if($previous_record && $request->input('from_date') <= $previous_record->from_date)
            return response()->json(['message' => trans('messages.back_date_entry'), 'status' => 'error']);

        if($request->has('to_date'))
            $location = UserLocation::whereUserId($user_location->user_id)->where('id','!=',$user_location->id)
                ->where(function ($query) use($request) { $query->where(function ($query) use($request){
                    $query->where('from_date','<=',$request->input('from_date'))
                    ->where('to_date','>=',$request->input('from_date'));
                    })->orWhere(function ($query) use($request) {
                        $query->where('from_date','<=',$request->input('to_date'))
                            ->where('to_date','>=',$request->input('to_date'));
                    });})->count();
        else
            $location = UserLocation::whereUserId($user_location->user_id)->where('id','!=',$user_location->id)->where('from_date','<=',$request->input('from_date'))
                        ->where('to_date','>=',$request->input('from_date'))->count();

        if($location)
            return response()->json(['message' => trans('messages.entry_already_defined'), 'status' => 'error']);

        $data = $request->all();
        $data['to_date'] = ($request->has('to_date')) ? $request->input('to_date') : null;
        $user_location->fill($data)->save();

        $profile = $user->Profile;
        $current_location_id = getUserLocation(date('Y-m-d'),$user->id);
        $profile->location_id = ($current_location_id) ? : null;
        $profile->save();
        updateCustomField($this->form,$user_location->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user->id, 'activity' => 'updated','sub_module' => 'location','sub_module_id' => $user_location->id]);

        return response()->json(['message' => trans('messages.location').' '.trans('messages.updated'), 'status' => 'success']);
    }

    public function destroy(Request $request, UserLocation $user_location){
        $user = $user_location->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if($user_location->location_id == $user->Profile->location_id){
            $profile = $user->Profile;
            $profile->location_id = null;
            $profile->save();
        }
        
        deleteCustomField($this->form, $user_location->id);

        $this->logActivity(['module' => 'user','module_id' => $user->id, 'activity' => 'deleted','sub_module' => 'location','sub_module_id' => $user_location->id]);

        $user_location->delete();

        return response()->json(['message' => trans('messages.location').' '.trans('messages.deleted'), 'status' => 'success']);
    }

}