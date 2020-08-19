<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\SalaryHeadRequest;
use Entrust;
use App\SalaryHead;

Class SalaryHeadController extends Controller{
    use BasicController;

	public function lists(Request $request){
		$salary_heads = SalaryHead::all();
		return view('salary_head.list',compact('salary_heads'))->render();
	}

	public function show(){
	}

	public function create(){
		return view('salary_head.create');
	}

	public function edit(SalaryHead $salary_head){
		return view('salary_head.edit',compact('salary_head'));
	}

	public function store(SalaryHeadRequest $request, SalaryHead $salary_head){	

		$data = $request->all();
		$salary_head->fill($data)->save();

		$this->logActivity(['module' => 'salary_head','module_id' => $salary_head->id,'activity' => 'added']);

    	$new_data = array('value' => $salary_head->name,'id' => $salary_head->id,'field' => 'salary_head_id');
        $response = ['message' => trans('messages.salary').' '.trans('messages.head').' '.trans('messages.added'), 'status' => 'success','new_data' => $new_data]; 
        return response()->json($response);
	}

	public function update(SalaryHeadRequest $request, SalaryHead $salary_head){

		$data = $request->all();
		$salary_head->fill($data)->save();

		$this->logActivity(['module' => 'salary_head','module_id' => $salary_head->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.salary').' '.trans('messages.head').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(SalaryHead $salary_head,Request $request){
		$this->logActivity(['module' => 'salary_head','module_id' => $salary_head->id,'activity' => 'deleted']);

        $salary_head->delete();
        
        return response()->json(['message' => trans('messages.salary').' '.trans('messages.head').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>