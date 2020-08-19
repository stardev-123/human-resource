<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Entrust;
use App\Email;

Class EmailController extends Controller{
    use BasicController;

	public function index(){

		$table_data['email-table'] = array(
			'source' => 'email',
			'title' => 'Email Log',
			'id' => 'email_table',
			'data' => array(
        		trans('messages.option'),
        		trans('messages.from'),
        		trans('messages.to'),
        		trans('messages.subject'),
        		trans('messages.date')
        		),
			'form' => 'email-log-filter-form'
			);

		$assets = ['datatable'];

		return view('email_log.index',compact('table_data','assets'));
	}

	public function lists(Request $request){
		$query = Email::whereNotNull('id');

        if($request->has('date_start') && $request->has('date_end'))
            $query->whereBetween('created_at',[$request->input('date_start').' 00:00:00',$request->input('date_end').' 23:59:59']);

        $emails = $query->get();
        
        $rows = array();

        foreach($emails as $email){

			$rows[] = array(
				'<div class="btn-group btn-group-xs">'.
				'<a href="#" data-href="/email/'.$email->id.'" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="'.trans('messages.view').'"></i></a></div>',
				$email->from_address,
				$email->to_address,
				$email->subject,
				showDateTime($email->created_at)
				);
        }
        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function show(Email $email){
		return view('email_log.show',compact('email'));
	}
}
?>