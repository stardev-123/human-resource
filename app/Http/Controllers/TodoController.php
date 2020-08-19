<?php
namespace App\Http\Controllers;
use Entrust;
use App\Todo;
use Illuminate\Http\Request;
use App\Http\Requests\TodoRequest;

Class TodoController extends Controller{
    use BasicController;

	protected $form = 'todo-form';

	public function __construct()
	{
		$this->middleware('feature_available:enable_to_do');
	}

	public function index(){
		return view('todo.create');
	}

	public function show(Todo $todo){
		return view('todo.edit');
	}

	public function create(){
	}

	public function edit(Todo $todo){
		
		if($todo->user_id != \Auth::user()->id)
            return view('global.error',['message' => trans('messages.permission_denied')]);

		return view('todo.edit',compact('todo'));
	}

	public function store(TodoRequest $request, Todo $todo){	
		$data = $request->all();
	    $todo->fill($data);
	    $todo->user_id = \Auth::user()->id;
		$todo->save();

		$this->logActivity(['module' => 'to_do','module_id' => $todo->id,'activity' => 'added']);

        return response()->json(['message' => trans('messages.to_do').' '.trans('messages.added'), 'status' => 'success']);
	}

	public function update(TodoRequest $request, Todo $todo){
		
		if($todo->user_id != \Auth::user()->id)
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

		$data = $request->all();
		$todo->fill($data)->save();
		$this->logActivity(['module' => 'to_do','module_id' => $todo->id,'activity' => 'updated']);
		
        return response()->json(['message' => trans('messages.to_do').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(Todo $todo,Request $request){
		if($todo->user_id != \Auth::user()->id)
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);
		
		$this->logActivity(['module' => 'to_do','module_id' => $todo->id,'activity' => 'deleted']);

        $todo->delete();
        return response()->json(['message' => trans('messages.to_do').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>