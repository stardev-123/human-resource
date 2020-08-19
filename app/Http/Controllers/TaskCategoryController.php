<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\TaskCategoryRequest;
use Entrust;
use App\TaskCategory;

Class TaskCategoryController extends Controller{
    use BasicController;

	public function lists(Request $request){
		$task_categories = TaskCategory::all();
		return view('task_category.list',compact('task_categories'))->render();
	}

	public function show(){
	}

	public function create(){
		return view('task_category.create');
	}

	public function edit(TaskCategory $task_category){
		return view('task_category.edit',compact('task_category'));
	}

	public function store(TaskCategoryRequest $request, TaskCategory $task_category){	

		$data = $request->all();
		$task_category->fill($data)->save();

		$this->logActivity(['module' => 'task_category','module_id' => $task_category->id,'activity' => 'added']);

    	$new_data = array('value' => $task_category->name,'id' => $task_category->id,'field' => 'task_category_id');
        $response = ['message' => trans('messages.task').' '.trans('messages.category').' '.trans('messages.added'), 'status' => 'success','new_data' => $new_data]; 
        return response()->json($response);
	}

	public function update(TaskCategoryRequest $request, TaskCategory $task_category){

		$data = $request->all();
		$task_category->fill($data)->save();

		$this->logActivity(['module' => 'task_category','module_id' => $task_category->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.task').' '.trans('messages.category').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(TaskCategory $task_category,Request $request){
		$this->logActivity(['module' => 'task_category','module_id' => $task_category->id,'activity' => 'deleted']);

        $task_category->delete();
        
        return response()->json(['message' => trans('messages.task').' '.trans('messages.category').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>