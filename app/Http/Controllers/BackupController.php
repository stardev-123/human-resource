<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Entrust;
use App\Backup;
use File;

Class BackupController extends Controller{
    use BasicController;

	public function __construct()
	{
		$this->middleware('feature_available:enable_backup');
	}

	public function index(){

		$table_data['backup-table'] = array(
			'source' => 'backup',
			'title' => 'Backup Log',
			'id' => 'backup_table',
			'data' => array(
        		trans('messages.option'),
        		trans('messages.file'),
        		trans('messages.date')
        		),
            'form' => 'backup-log-filter-form',
			);

		$assets = ['datatable'];

		return view('backup_log.index',compact('table_data','assets'));
	}

	public function lists(Request $request){
        $query = \App\Backup::whereNotNull('id');

        if($request->has('date_start') && $request->has('date_end'))
            $query->whereBetween('created_at',[$request->input('date_start').' 00:00:00',$request->input('date_end').' 23:59:59']);

        $backups = $query->orderBy('created_at','desc')->get();
        
        $rows = array();

        foreach($backups as $backup){

			$rows[] = array(
				'<div class="btn-group btn-group-xs">'.
				'<a href="/backup/'.$backup->id.'/download" class="btn btn-xs btn-default" > <i class="fa fa-download" data-toggle="tooltip" title="'.trans('messages.download').'"></i></a>'.
				delete_form(['backup.destroy',$backup->id]).
				'</div>',
				$backup->file,
				showDateTime($backup->created_at)
				);
        }
        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function download($id){

		$backup = Backup::find($id);

		if(!$backup)
            return redirect('/backup')->withErrors(trans('messages.invalid_link'));

        if(!getMode())
            return redirect('/backup')->withErrors(trans('messages.disable_message'));

        if(!\Storage::exists('backup/'.$backup->file))
            return redirect('/backup')->withErrors(trans('messages.file_not_found'));

        $download_path = storage_path().config('constant.storage_root').'backup/'.$backup->file;

        return response()->download($download_path);
	}

	public function store(Request $request){
		if($request->has('delete_old_backup')){
			Backup::truncate();
			\Storage::deleteDirectory('backup');
		}

        include('../app/Helper/Dumper.php');
        $data = backupDatabase();

        if($data['status'] == 'error')
            return response()->json(['status' => 'error','message' => $data]);

        $filename = $data['filename'];
        $backup = \App\Backup::create(['file' => $filename]);

		$this->logActivity(['module' => 'backup','module_id' => $backup->id,'activity' => 'generated']);
        return response()->json(['message' => trans('messages.backup').' '.trans('messages.generated'), 'status' => 'success']);
	}

	public function destroy(Backup $backup,Request $request){

		$this->logActivity(['module' => 'backup','module_id' => $backup->id,'activity' => 'deleted']);

		\Storage::delete('backup/'.$backup->file);
        $backup->delete();
        
        return response()->json(['message' => trans('messages.backup').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>