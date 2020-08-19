<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\TaskPriorityRequest;
use Entrust;
use App\TaskPriority;

Class TaskPriorityController extends Controller{
    use BasicController;

	public function lists(Request $request){
		$task_priorities = TaskPriority::all();
		return view('task_priority.list',compact('task_priorities'))->render();
	}

	public function show(){
	}

	public function create(){
		return view('task_priority.create');
	}

	public function edit(TaskPriority $task_priority){
		return view('task_priority.edit',compact('task_priority'));
	}

	public function store(TaskPriorityRequest $request, TaskPriority $task_priority){	

		$data = $request->all();
		$task_priority->fill($data)->save();

		$this->logActivity(['module' => 'task_priority','module_id' => $task_priority->id,'activity' => 'added']);

    	$new_data = array('value' => $task_priority->name,'id' => $task_priority->id,'field' => 'task_priority_id');
        $response = ['message' => trans('messages.task').' '.trans('messages.priority').' '.trans('messages.added'), 'status' => 'success','new_data' => $new_data]; 
        return response()->json($response);
	}

	public function update(TaskPriorityRequest $request, TaskPriority $task_priority){

		$data = $request->all();
		$task_priority->fill($data)->save();

		$this->logActivity(['module' => 'task_priority','module_id' => $task_priority->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.task').' '.trans('messages.priority').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(TaskPriority $task_priority,Request $request){
		$this->logActivity(['module' => 'task_priority','module_id' => $task_priority->id,'activity' => 'deleted']);

        $task_priority->delete();
        
        return response()->json(['message' => trans('messages.task').' '.trans('messages.priority').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>