<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\EducationLevelRequest;
use Entrust;
use App\EducationLevel;

Class EducationLevelController extends Controller{
    use BasicController;

	public function lists(Request $request){
		$education_levels = EducationLevel::all();
		return view('education_level.list',compact('education_levels'))->render();
	}

	public function show(){
	}

	public function create(){
		return view('education_level.create');
	}

	public function edit(EducationLevel $education_level){
		return view('education_level.edit',compact('education_level'));
	}

	public function store(EducationLevelRequest $request, EducationLevel $education_level){	

		$data = $request->all();
		$education_level->fill($data)->save();

		$this->logActivity(['module' => 'education_level','module_id' => $education_level->id,'activity' => 'added']);

    	$new_data = array('value' => $education_level->name,'id' => $education_level->id,'field' => 'education_level_id');
        $response = ['message' => trans('messages.education').' '.trans('messages.level').' '.trans('messages.added'), 'status' => 'success','new_data' => $new_data]; 
        return response()->json($response);
	}

	public function update(EducationLevelRequest $request, EducationLevel $education_level){

		$data = $request->all();
		$education_level->fill($data)->save();

		$this->logActivity(['module' => 'education_level','module_id' => $education_level->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.education').' '.trans('messages.level').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(EducationLevel $education_level,Request $request){
		$this->logActivity(['module' => 'education_level','module_id' => $education_level->id,'activity' => 'deleted']);

        $education_level->delete();
        
        return response()->json(['message' => trans('messages.education').' '.trans('messages.level').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>