<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\LeaveTypeRequest;
use Entrust;
use App\LeaveType;

Class LeaveTypeController extends Controller{
    use BasicController;

	public function lists(Request $request){
		$leave_types = LeaveType::all();
		return view('leave_type.list',compact('leave_types'))->render();
	}

	public function show(){
	}

	public function create(){
		return view('leave_type.create');
	}

	public function edit(LeaveType $leave_type){
		return view('leave_type.edit',compact('leave_type'));
	}

	public function store(LeaveTypeRequest $request, LeaveType $leave_type){	

		$data = $request->all();
		$leave_type->fill($data);

		if($request->input('is_half_day')){
			LeaveType::where('id','!=',$leave_type->id)->update(['is_half_day' => 0]);
			$leave_type->is_half_day = 1;
		}

		$leave_type->save();

		$this->logActivity(['module' => 'leave_type','module_id' => $leave_type->id,'activity' => 'added']);

    	$new_data = array('value' => $leave_type->name,'id' => $leave_type->id,'field' => 'leave_type_id');
        $response = ['message' => trans('messages.leave').' '.trans('messages.type').' '.trans('messages.added'), 'status' => 'success','new_data' => $new_data]; 
        return response()->json($response);
	}

	public function update(LeaveTypeRequest $request, LeaveType $leave_type){

		$data = $request->all();
		$leave_type->fill($data);
		
		if($request->input('is_half_day')){
			LeaveType::where('id','!=',$leave_type->id)->update(['is_half_day' => 0]);
			$leave_type->is_half_day = 1;
		}

		$leave_type->save();

		$this->logActivity(['module' => 'leave_type','module_id' => $leave_type->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.leave').' '.trans('messages.type').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(LeaveType $leave_type,Request $request){
		$this->logActivity(['module' => 'leave_type','module_id' => $leave_type->id,'activity' => 'deleted']);

        $leave_type->delete();
        
        return response()->json(['message' => trans('messages.leave').' '.trans('messages.type').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>