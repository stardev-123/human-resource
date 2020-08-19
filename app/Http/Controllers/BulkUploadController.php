<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Entrust;
use App\BulkUpload;

Class BulkUploadController extends Controller{
    use BasicController;

    public function getColumnNumber($module){
    	if($module == 'attendance')
    		return 6;
    }

    public function getColumnName($module){
    	if($module == 'attendance'){
            return [
                'a' => 'employee_code',
                'b' => 'date',
                'c' => 'clock_in_date',
                'd' => 'clock_in_time',
                'e' => 'clock_out_date',
                'f' => 'clock_out_time',
            ];
    	}
    }

    public function getColumnOption($module){
        if($module == 'attendance'){
            return [
                'employee_code' => trans('messages.user').' '.trans('messages.code'),
                'date' => trans('messages.date'),
                'clock_in_date' => trans('messages.clock_in').' '.trans('messages.date'),
                'clock_in_time' => trans('messages.clock_in').' '.trans('messages.time'),
                'clock_out_date' => trans('messages.clock_out').' '.trans('messages.date'),
                'clock_out_time' => trans('messages.clock_out').' '.trans('messages.time')
            ];
        }
    }

	public function uploadColumn($module, Request $request){

		if(!in_array($module,['attendance']))
            return redirect()->back()->withErrors(trans('messages.invalid_link'));

        if(!Entrust::can('upload-'.$module))
            return redirect()->back()->withErrors(trans('messages.permission_denied'));

        if(!$request->file('file'))
            return redirect()->back()->withErrors(trans('messages.validation_required',['attribute' => 'file']));

		$filename = getUuid();
        $extension = $request->file('file')->getClientOriginalExtension();

        $allowed_file_types = ['csv'];
        if(!in_array($extension, $allowed_file_types))
            return redirect()->back()->withErrors(trans('messages.file_allowed_to_bulk_upload',['attribute' => 'csv']));

        $file = $request->file('file')->storeAs('temp_bulk_upload',$filename.".".$extension);
        $filename_extension = storage_path().config('constant.storage_root').'temp_bulk_upload/'.$filename.".".$extension;
        session(['user_upload_file' => $filename.'.'.$extension]);

        include('../app/Helper/ExcelReader/SpreadsheetReader.php');

        $data = array();
        $xls_datas = array();
        $Reader = new \SpreadsheetReader($filename_extension);
        $i = 0;
        foreach ($Reader as $key => $row){
            $i++;
            if($i<=5){
            	$no_of_column = $this->getColumnNumber($module);
            	for($j = 0; $j < $no_of_column; $j++)
            		$data[getAlphabet($j)] = array_key_exists($j, $row) ? $row[$j] : null;
            	$xls_datas[] = $data;
            	unset($data);
            }
        }

        if(!count($xls_datas))
            return redirect()->back()->withErrors(trans('messages.no_data_found'));
        $columns = $this->getColumnName($module);
        $column_options = $this->getColumnOption($module);
        return view('bulk_upload.index',compact('xls_datas','columns','module','column_options'));
	}

    public function log(){
        $table_data['upload-log-table'] = array(
            'source' => 'upload-log',
            'title' => toWordTranslate('upload-log'),
            'id' => 'upload_log_table',
            'data' => array(
                trans('messages.option'),
                trans('messages.module'),
                trans('messages.total'),
                trans('messages.w_uploaded'),
                trans('messages.w_rejected'),
                trans('messages.user'),
                trans('messages.created_at')
                ),
            'form' => 'upload-log-filter-form'
            );

        $assets = ['datatable'];

        return view('bulk_upload.log',compact('table_data','assets'));
    }

    public function lists(Request $request){
        $query = BulkUpload::whereNotNull('id');

        if($request->has('date_start') && $request->has('date_end'))
            $query->whereBetween('created_at',[$request->input('date_start').' 00:00:00',$request->input('date_end').' 23:59:59']);

        $bulk_uploads = $query->get();
        
        $rows = array();

        foreach($bulk_uploads as $bulk_upload){

            $rows[] = array(
                '<div class="btn-group btn-group-xs">'.
                '<a href="/upload-log/'.$bulk_upload->uuid.'" class="btn btn-xs btn-default"> <i class="fa fa-download" data-toggle="tooltip" title="'.trans('messages.download').'"></i></a></div>',
                trans('messages.'.$bulk_upload->module),
                $bulk_upload->total,
                $bulk_upload->uploaded,
                $bulk_upload->rejected,
                $bulk_upload->User->name_with_designation_and_department,
                showDateTime($bulk_upload->created_at)
                );
        }
        $list['aaData'] = $rows;
        return json_encode($list);
    }

    public function download($id){
        $bulk_upload = BulkUpload::whereUuid($id)->first();

        if(!$bulk_upload)
            return redirect('/upload-log')->withErrors(trans('messages.invalid_link'));

        if(!\Storage::exists('failed_bulk_upload/'.$bulk_upload->uuid.'.csv'))
            return redirect('/upload-log')->withErrors(trans('messages.file_not_found'));

        $download_path = storage_path().config('constant.storage_root').'failed_bulk_upload/'.$bulk_upload->uuid.'.csv';

        return response()->download($download_path, $bulk_upload->uuid.'.csv');
    }
}