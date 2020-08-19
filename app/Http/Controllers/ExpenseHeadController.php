<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\ExpenseHeadRequest;
use Entrust;
use App\ExpenseHead;

Class ExpenseHeadController extends Controller{
    use BasicController;

	public function lists(Request $request){
		$expense_heads = ExpenseHead::all();
		return view('expense_head.list',compact('expense_heads'))->render();
	}

	public function show(){
	}

	public function create(){
		return view('expense_head.create');
	}

	public function edit(ExpenseHead $expense_head){
		return view('expense_head.edit',compact('expense_head'));
	}

	public function store(ExpenseHeadRequest $request, ExpenseHead $expense_head){	

		$data = $request->all();
		$expense_head->fill($data)->save();

		$this->logActivity(['module' => 'expense_head','module_id' => $expense_head->id,'activity' => 'added']);

    	$new_data = array('value' => $expense_head->name,'id' => $expense_head->id,'field' => 'expense_head_id');
        $response = ['message' => trans('messages.expense').' '.trans('messages.head').' '.trans('messages.added'), 'status' => 'success','new_data' => $new_data]; 
        return response()->json($response);
	}

	public function update(ExpenseHeadRequest $request, ExpenseHead $expense_head){

		$data = $request->all();
		$expense_head->fill($data)->save();

		$this->logActivity(['module' => 'expense_head','module_id' => $expense_head->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.expense').' '.trans('messages.head').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(ExpenseHead $expense_head,Request $request){
		$this->logActivity(['module' => 'expense_head','module_id' => $expense_head->id,'activity' => 'deleted']);

        $expense_head->delete();
        
        return response()->json(['message' => trans('messages.expense').' '.trans('messages.head').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>