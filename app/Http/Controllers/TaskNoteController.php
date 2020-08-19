<?php
namespace App\Http\Controllers;
use App\TaskNote;
use Illuminate\Http\Request;

Class TaskNoteController extends Controller{
    use BasicController;

	public function store(Request $request,$id){

		$note = TaskNote::firstOrNew(['task_id' => $id,'user_id' => \Auth::user()->id]);
		$note->note = $request->input('note');
	    $note->save();
		$this->logActivity(['module' => 'task','sub_module' => 'note','module_id' => $id,'sub_module_id' => $note->id, 'activity' => 'saved']);
	    
        return response()->json(['message' => trans('messages.note').' '.trans('messages.saved'), 'status' => 'success']);
	}
}
?>