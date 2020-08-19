<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\TicketCategoryRequest;
use Entrust;
use App\TicketCategory;

Class TicketCategoryController extends Controller{
    use BasicController;

	public function lists(Request $request){
		$ticket_categories = TicketCategory::all();
		return view('ticket_category.list',compact('ticket_categories'))->render();
	}

	public function show(){
	}

	public function create(){
		return view('ticket_category.create');
	}

	public function edit(TicketCategory $ticket_category){
		return view('ticket_category.edit',compact('ticket_category'));
	}

	public function store(TicketCategoryRequest $request, TicketCategory $ticket_category){	

		$data = $request->all();
		$ticket_category->fill($data)->save();

		$this->logActivity(['module' => 'ticket_category','module_id' => $ticket_category->id,'activity' => 'added']);

    	$new_data = array('value' => $ticket_category->name,'id' => $ticket_category->id,'field' => 'ticket_category_id');
        $response = ['message' => trans('messages.ticket').' '.trans('messages.category').' '.trans('messages.added'), 'status' => 'success','new_data' => $new_data]; 
        return response()->json($response);
	}

	public function update(TicketCategoryRequest $request, TicketCategory $ticket_category){

		$data = $request->all();
		$ticket_category->fill($data)->save();

		$this->logActivity(['module' => 'ticket_category','module_id' => $ticket_category->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.ticket').' '.trans('messages.category').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(TicketCategory $ticket_category,Request $request){
		$this->logActivity(['module' => 'ticket_category','module_id' => $ticket_category->id,'activity' => 'deleted']);

        $ticket_category->delete();
        
        return response()->json(['message' => trans('messages.ticket').' '.trans('messages.category').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>