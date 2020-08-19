<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\TicketPriorityRequest;
use Entrust;
use App\TicketPriority;

Class TicketPriorityController extends Controller{
    use BasicController;

	public function lists(Request $request){
		$ticket_priorities = TicketPriority::all();
		return view('ticket_priority.list',compact('ticket_priorities'))->render();
	}

	public function show(){
	}

	public function create(){
		return view('ticket_priority.create');
	}

	public function edit(TicketPriority $ticket_priority){
		return view('ticket_priority.edit',compact('ticket_priority'));
	}

	public function store(TicketPriorityRequest $request, TicketPriority $ticket_priority){	

		$data = $request->all();
		$ticket_priority->fill($data)->save();

		$this->logActivity(['module' => 'ticket_priority','module_id' => $ticket_priority->id,'activity' => 'added']);

    	$new_data = array('value' => $ticket_priority->name,'id' => $ticket_priority->id,'field' => 'ticket_priority_id');
        $response = ['message' => trans('messages.ticket').' '.trans('messages.priority').' '.trans('messages.added'), 'status' => 'success','new_data' => $new_data]; 
        return response()->json($response);
	}

	public function update(TicketPriorityRequest $request, TicketPriority $ticket_priority){

		$data = $request->all();
		$ticket_priority->fill($data)->save();

		$this->logActivity(['module' => 'ticket_priority','module_id' => $ticket_priority->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.ticket').' '.trans('messages.priority').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(TicketPriority $ticket_priority,Request $request){
		$this->logActivity(['module' => 'ticket_priority','module_id' => $ticket_priority->id,'activity' => 'deleted']);

        $ticket_priority->delete();
        
        return response()->json(['message' => trans('messages.ticket').' '.trans('messages.priority').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>