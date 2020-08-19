<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\ContractTypeRequest;
use Entrust;
use App\ContractType;

Class ContractTypeController extends Controller{
    use BasicController;

	public function lists(Request $request){
		$contract_types = ContractType::all();
		return view('contract_type.list',compact('contract_types'))->render();
	}

	public function show(){
	}

	public function create(){
		return view('contract_type.create');
	}

	public function edit(ContractType $contract_type){
		return view('contract_type.edit',compact('contract_type'));
	}

	public function store(ContractTypeRequest $request, ContractType $contract_type){	

		$data = $request->all();
		$contract_type->fill($data)->save();

		$this->logActivity(['module' => 'contract_type','module_id' => $contract_type->id,'activity' => 'added']);

    	$new_data = array('value' => $contract_type->name,'id' => $contract_type->id,'field' => 'contract_type_id');
        $response = ['message' => trans('messages.contract').' '.trans('messages.type').' '.trans('messages.added'), 'status' => 'success','new_data' => $new_data]; 
        return response()->json($response);
	}

	public function update(ContractTypeRequest $request, ContractType $contract_type){

		$data = $request->all();
		$contract_type->fill($data)->save();

		$this->logActivity(['module' => 'contract_type','module_id' => $contract_type->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.contract').' '.trans('messages.type').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(ContractType $contract_type,Request $request){
		$this->logActivity(['module' => 'contract_type','module_id' => $contract_type->id,'activity' => 'deleted']);

        $contract_type->delete();
        
        return response()->json(['message' => trans('messages.contract').' '.trans('messages.type').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>