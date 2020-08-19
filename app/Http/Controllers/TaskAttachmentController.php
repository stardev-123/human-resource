<?php
namespace App\Http\Controllers;
use App\TaskAttachment;
use File;
use Illuminate\Http\Request;
use App\Http\Requests\TaskAttachmentRequest;

Class TaskAttachmentController extends Controller{
    use BasicController;

	public function store(TaskAttachmentRequest $request,$id){

        $upload_validation = validateUpload('task-attachment',$request);

        if($upload_validation['status'] == 'error')
            return response()->json($upload_validation);

		$task_attachment = new TaskAttachment;
		$task_attachment->title = $request->input('title');
		$task_attachment->description = $request->input('description');
		$task_attachment->user_id = \Auth::user()->id;
		$task_attachment->task_id = $id;
		$task_attachment->save();
        storeUpload('task-attachment',$task_attachment->id,$request);
	    
		$this->logActivity(['module' => 'task','sub_module' => 'attachment','module_id' => $id,'sub_module_id' => $task_attachment->id, 'activity' => 'added']);

        return response()->json(['message' => trans('messages.attachment').' '.trans('messages.added'), 'status' => 'success']);
	}

	public function lists(Request $request){
        $task = \App\Task::find($request->input('task_id'));
        return view('task.attachment_list',compact('task'))->render();
	}

    public function download($id){
        $upload = \App\Upload::whereUuid($id)->whereModule('task-attachment')->whereStatus(1)->first();

        if(!$upload)
            return redirect('/task')->withErrors(trans('messages.invalid_link'));

        $task_attachment = TaskAttachment::find($upload->module_id);

        if(!$task_attachment)
            return redirect('/task')->withErrors(trans('messages.invalid_link'));

        if(!$this->taskAccessible($task_attachment->task_id))
            return redirect('/task')->withErrors(trans('messages.permission_denied'));

        if(!\Storage::exists('attachments/'.$upload->attachments))
            return redirect('/task/'.$task_attachment->task_id)->withErrors(trans('messages.file_not_found'));

        $download_path = storage_path().config('constant.storage_root').'attachments/'.$upload->attachments;

        return response()->download($download_path, $upload->user_filename);
    }

	public function destroy($id,Request $request){

		$task_attachment = TaskAttachment::find($id);

		if($task_attachment->user_id != \Auth::user()->id)
            return response()->json(['message' => trans('messages.invalid_link'), 'status' => 'error']);

        deleteUpload('task-attachment',$task_attachment->id);
        
		$this->logActivity(['module' => 'task','sub_module' => 'attachment','module_id' => $task_attachment->Task->id,'sub_module_id' => $task_attachment->id, 'activity' => 'deleted']);
		$id = $task_attachment->Task->id;
		$task_attachment->delete();
		
        return response()->json(['message' => trans('messages.attachment').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>