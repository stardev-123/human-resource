<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\QualificationLanguageRequest;
use Entrust;
use App\QualificationLanguage;

Class QualificationLanguageController extends Controller{
    use BasicController;

	public function lists(Request $request){
		$qualification_languages = QualificationLanguage::all();
		return view('qualification_language.list',compact('qualification_languages'))->render();
	}

	public function show(){
	}

	public function create(){
		return view('qualification_language.create');
	}

	public function edit(QualificationLanguage $qualification_language){
		return view('qualification_language.edit',compact('qualification_language'));
	}

	public function store(QualificationLanguageRequest $request, QualificationLanguage $qualification_language){	

		$data = $request->all();
		$qualification_language->fill($data)->save();

		$this->logActivity(['module' => 'qualification_language','module_id' => $qualification_language->id,'activity' => 'added']);

    	$new_data = array('value' => $qualification_language->name,'id' => $qualification_language->id,'field' => 'qualification_language_id');
        $response = ['message' => trans('messages.qualification').' '.trans('messages.language').' '.trans('messages.added'), 'status' => 'success','new_data' => $new_data]; 
        return response()->json($response);
	}

	public function update(QualificationLanguageRequest $request, QualificationLanguage $qualification_language){

		$data = $request->all();
		$qualification_language->fill($data)->save();

		$this->logActivity(['module' => 'qualification_language','module_id' => $qualification_language->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.qualification').' '.trans('messages.language').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(QualificationLanguage $qualification_language,Request $request){
		$this->logActivity(['module' => 'qualification_language','module_id' => $qualification_language->id,'activity' => 'deleted']);

        $qualification_language->delete();
        
        return response()->json(['message' => trans('messages.qualification').' '.trans('messages.language').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>