<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\DocumentTypeRequest;
use Entrust;
use App\DocumentType;

Class DocumentTypeController extends Controller{
    use BasicController;

	public function lists(Request $request){
		$document_types = DocumentType::all();
		return view('document_type.list',compact('document_types'))->render();
	}

	public function show(){
	}

	public function create(){
		return view('document_type.create');
	}

	public function edit(DocumentType $document_type){
		return view('document_type.edit',compact('document_type'));
	}

	public function store(DocumentTypeRequest $request, DocumentType $document_type){	

		$data = $request->all();
		$document_type->fill($data)->save();

		$this->logActivity(['module' => 'document_type','module_id' => $document_type->id,'activity' => 'added']);

    	$new_data = array('value' => $document_type->name,'id' => $document_type->id,'field' => 'document_type_id');
        $response = ['message' => trans('messages.document').' '.trans('messages.type').' '.trans('messages.added'), 'status' => 'success','new_data' => $new_data]; 
        return response()->json($response);
	}

	public function update(DocumentTypeRequest $request, DocumentType $document_type){

		$data = $request->all();
		$document_type->fill($data)->save();

		$this->logActivity(['module' => 'document_type','module_id' => $document_type->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.document').' '.trans('messages.type').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(DocumentType $document_type,Request $request){
		$this->logActivity(['module' => 'document_type','module_id' => $document_type->id,'activity' => 'deleted']);

        $document_type->delete();
        
        return response()->json(['message' => trans('messages.document').' '.trans('messages.type').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>