<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\TicketRequest;
use Entrust;
use App\Ticket;

Class TicketController extends Controller{
    use BasicController;

	protected $form = 'ticket-form';

	public function isDeletable($ticket){
		if(defaultRole())
			return 1;

		if(!Entrust::can('delete-ticket'))
			return 0;

		if($ticket->user_id == \Auth::user()->id && $ticket->status != 'open')
			return 0;

		if(!in_array($ticket->user_id,getAccessibleUserId()))
			return 0;

		return 1;
	}

	public function isAccessible($ticket){
		if(!in_array($ticket->user_id,getAccessibleUserId(\Auth::user()->id,1)))
			return 0;

		return 1;
	}

	public function index(){
		if(!Entrust::can('list-ticket'))
			return redirect('/home')->withErrors(trans('messages.permission_denied'));

		$data = array(
	        		trans('messages.option'),
	        		trans('messages.user'),
	        		trans('messages.subject'),
	        		trans('messages.category'),
	        		trans('messages.priority'),
	        		trans('messages.status'),
	        		trans('messages.created_at')
        		);

		$data = putCustomHeads($this->form, $data);

		$table_data['ticket-table'] = array(
				'source' => 'ticket',
				'title' => trans('messages.ticket').' '.trans('messages.list'),
				'id' => 'ticket_table',
				'data' => $data,
				'form' => 'ticket-filter-form'
			);

		$ticket_priorities = \App\TicketPriority::all()->pluck('name','id')->all();
		$ticket_categories = \App\TicketCategory::all()->pluck('name','id')->all();
		$users = getAccessibleUserList(\Auth::user()->id,1);

		$assets = ['datatable','summernote','graph'];
		$menu = 'ticket';
		return view('ticket.index',compact('table_data','assets','menu','ticket_priorities','ticket_categories','users'));
	}

	public function lists(Request $request){
		if(!Entrust::can('list-ticket'))
			return;

		$query = Ticket::whereIn('user_id',getAccessibleUserId(\Auth::user()->id,1));

		if($request->has('subject'))
			$query->where('subject','like','%'.$request->input('subject').'%');

		if($request->has('ticket_category_id'))
			$query->whereIn('ticket_category_id',$request->input('ticket_category_id'));
		
		if($request->has('ticket_priority_id'))
			$query->whereIn('ticket_priority_id',$request->input('ticket_priority_id'));
		
		if($request->has('user_id'))
			$query->whereIn('user_id',$request->input('user_id'));

		if($request->has('status'))
			$query->whereIn('status',$request->input('status'));

        if($request->has('created_at_start') && $request->has('created_at_end'))
        	$query->whereBetween('created_at',[$request->input('created_at_start').' 00:00:00',$request->input('created_at_end').' 23:59:59']);

        $tickets = $query->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();

        foreach($tickets as $ticket){

			$row = array(
				'<div class="btn-group btn-group-xs">'.
				'<a href="/ticket/'.$ticket->uuid.'" class="btn btn-xs btn-default"> <i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="'.trans('messages.view').'"></i></a> '.
				($this->isDeletable($ticket) ? delete_form(['ticket.destroy',$ticket->id]) : '').
				'</div>',
				$ticket->User->name_with_designation_and_department,
				$ticket->subject,
				$ticket->TicketCategory->name,
				$ticket->TicketPriority->name,
				getTicketStatus($ticket->status),
				showDateTime($ticket->created_at)
				);
			$id = $ticket->id;

			foreach($col_ids as $col_id)
				array_push($row,isset($values[$id][$col_id]) ? $values[$id][$col_id] : '');
			$rows[] = $row;
        }
        $list['aaData'] = $rows;

        $ticket_categories = array();
        $ticket_priorities = array();
        $ticket_statuses = array();
        $departments = array();
        foreach($tickets as $ticket){
            $ticket_categories[] = $ticket->TicketCategory->name;
            $ticket_priorities[] = $ticket->TicketPriority->name;
            $ticket_statuses[] = trans('messages.'.$ticket->status);
            if($ticket->User->department_name)
            	$departments[] = $ticket->User->department_name;
        }

        $list['graph']['ticket_category'] = getPieCharData($ticket_categories,'category-wise-ticket-graph');
        $list['graph']['ticket_priority'] = getPieCharData($ticket_priorities,'priority-wise-ticket-graph');
        $list['graph']['ticket_status'] = getPieCharData($ticket_statuses,'status-wise-ticket-graph');
        $list['graph']['ticket_department'] = getPieCharData($departments,'departments-wise-ticket-graph');

        return json_encode($list);
	}

	public function show($uuid){
		$ticket = Ticket::whereUuid($uuid)->first();

		if(!$ticket || !$this->isAccessible($ticket))
			return redirect('/ticket')->withErrors(trans('messages.permission_denied'));

		$ticket_uploads = \App\Upload::whereModule('ticket')->whereModuleId($ticket->id)->whereStatus(1)->get();

		$ticket_priorities = \App\TicketPriority::all()->pluck('name','id')->all();
		$ticket_categories = \App\TicketCategory::all()->pluck('name','id')->all();

		$this->updateNotification(['module' => 'ticket','module_id' => $ticket->id]);

		$assets = ['summernote'];

		return view('ticket.show',compact('ticket','ticket_uploads','assets','ticket_priorities','ticket_categories'));
	}

	public function detail(Request $request){
		$ticket = Ticket::whereUuid($request->input('uuid'))->first();

		if(!$ticket || !$this->isAccessible($ticket))
			return;

        $custom_fields = \App\CustomField::whereForm($this->form)->get();
        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
		return view('ticket.detail',compact('ticket','custom_fields','col_ids','values'))->render();
	}

	public function reply(Request $request){
		$ticket = Ticket::whereUuid($request->input('uuid'))->first();

		if(!$ticket || !$this->isAccessible($ticket))
			return;

		return view('ticket.reply',compact('ticket'))->render();
	}

	public function storeReply($uuid, Request $request){
		$ticket = Ticket::whereUuid($uuid)->first();

		if(!$ticket || !$this->isAccessible($ticket))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(strip_tags($request->input('description')) == '')
            return response()->json(['message' => trans('messages.validation_required',['attribute' => trans('messages.reply')]), 'status' => 'error']);

        $upload_validation = validateUpload('ticket-reply',$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

        $ticket_reply = new \App\TicketReply;
        $ticket_reply->description = clean($request->input('description'),'custom');
        $ticket_reply->status = $request->input('status');
	    $ticket_reply->user_id = \Auth::user()->id;
	    $ticket_reply->ticket_id = $ticket->id;
		$ticket_reply->save();

		$old_ticket_status = $ticket->status;

		$ticket->status = $request->has('status') ? $request->input('status') : $ticket->status;
		$ticket->ticket_priority_id = $request->has('ticket_priority_id') ? $request->input('ticket_priority_id') : $ticket->ticket_priority_id;
		$ticket->ticket_category_id = $request->has('ticket_category_id') ? $request->input('ticket_category_id') : $ticket->ticket_category_id;
		$ticket->save();
        storeUpload('ticket-reply',$ticket_reply->id,$request);

        if($ticket->user_id == \Auth::user()->id)
        	$notification_users = implode(',',getParentUserId(\Auth::user()->Profile->designation_id));
        else{
        	$notification_users = $ticket->user_id;
        	if($old_ticket_status != 'close' && $ticket->status == 'close')
        		$this->sendNotification(['module' => 'ticket','module_id' => $ticket->id,'url' => '/ticket/'.$ticket->uuid,'user' => $notification_users,'action' => 'close-ticket']);
        }
        $this->sendNotification(['module' => 'ticket','module_id' => $ticket->id,'url' => '/ticket/'.$ticket->uuid,'user' => $notification_users,'action' => 'reply-ticket']);

		$this->logActivity(['module' => 'ticket','module_id' => $ticket->id,'sub_module' => 'reply','sub_module_id' => $ticket_reply->id,'activity' => 'added']);

        return response()->json(['message' => trans('messages.reply').' '.trans('messages.sent'), 'status' => 'success']);
	}

	public function store(TicketRequest $request, Ticket $ticket){
		if(!Entrust::can('create-ticket'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);
	
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $upload_validation = validateUpload('ticket',$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

		$data = $request->all();
	    $ticket->fill($data);
	    $ticket->status = 'open';
	    $ticket->uuid = getUuid();
	    $ticket->description = clean($request->input('description'),'custom');
	    $ticket->user_id = \Auth::user()->id;
		$ticket->save();
		$this->logActivity(['module' => 'ticket','module_id' => $ticket->id,'activity' => 'added']);
		storeCustomField($this->form,$ticket->id, $data);
        storeUpload('ticket',$ticket->id,$request);

        $notification_users = implode(',',getParentUserId(\Auth::user()->Profile->designation_id));
        $this->sendNotification(['module' => 'ticket','module_id' => $ticket->id,'url' => '/ticket/'.$ticket->uuid,'user' => $notification_users,'action' => 'create-ticket']);

        return response()->json(['message' => trans('messages.ticket').' '.trans('messages.added'), 'status' => 'success']);
	}

	public function destroy(Request $request, Ticket $ticket){
		if(!$this->isDeletable($ticket))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		deleteUpload('ticket',$ticket->id);

		$this->logActivity(['module' => 'ticket','module_id' => $ticket->id,'activity' => 'deleted']);

		deleteCustomField($this->form, $ticket->id);
		$ticket->delete();
        return response()->json(['message' => trans('messages.ticket').' '.trans('messages.deleted'), 'status' => 'success']);
	}

}