<?php
namespace App\Http\Controllers;
use App\TaskComment;
use Illuminate\Http\Request;
use App\Http\Requests\TaskCommentRequest;

Class TaskCommentController extends Controller{
    use BasicController;

	public function store(TaskCommentRequest $request, $id){

		$task_comment = new TaskComment;
	    $task_comment->fill($request->all());
	    $task_comment->comment = clean($request->input('comment'),'custom');
	    $task_comment->task_id = $id;
	    $task_comment->user_id = \Auth::user()->id;
	    $task_comment->save();
		$this->logActivity(['module' => 'task','sub_module' => 'comment','module_id' => $id,'sub_module_id' => $task_comment->id,'activity' => 'added']);
	    
        return response()->json(['message' => trans('messages.comment').' '.trans('messages.posted'), 'status' => 'success']);
	}

	public function destroy($id,Request $request){

		$task_comment = TaskComment::find($id);

		if($task_comment->user_id != \Auth::user()->id)
            return response()->json(['message' => trans('messages.invalid_link'), 'status' => 'error']);

		$this->logActivity(['module' => 'task','sub_module' => 'comment','sub_module_id' => $task_comment->id,'module_id' => $task_comment->Task->id, 'activity' => 'deleted']);
		$id = $task_comment->Task->id;
		$task_comment->delete();
		
        return response()->json(['message' => trans('messages.comment').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>