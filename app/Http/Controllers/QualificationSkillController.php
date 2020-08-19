<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\QualificationSkillRequest;
use Entrust;
use App\QualificationSkill;

Class QualificationSkillController extends Controller{
    use BasicController;

	public function lists(Request $request){
		$qualification_skills = QualificationSkill::all();
		return view('qualification_skill.list',compact('qualification_skills'))->render();
	}

	public function show(){
	}

	public function create(){
		return view('qualification_skill.create');
	}

	public function edit(QualificationSkill $qualification_skill){
		return view('qualification_skill.edit',compact('qualification_skill'));
	}

	public function store(QualificationSkillRequest $request, QualificationSkill $qualification_skill){	

		$data = $request->all();
		$qualification_skill->fill($data)->save();

		$this->logActivity(['module' => 'qualification_skill','module_id' => $qualification_skill->id,'activity' => 'added']);

    	$new_data = array('value' => $qualification_skill->name,'id' => $qualification_skill->id,'field' => 'qualification_skill_id');
        $response = ['message' => trans('messages.qualification').' '.trans('messages.skill').' '.trans('messages.added'), 'status' => 'success','new_data' => $new_data]; 
        return response()->json($response);
	}

	public function update(QualificationSkillRequest $request, QualificationSkill $qualification_skill){

		$data = $request->all();
		$qualification_skill->fill($data)->save();

		$this->logActivity(['module' => 'qualification_skill','module_id' => $qualification_skill->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.qualification').' '.trans('messages.skill').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(QualificationSkill $qualification_skill,Request $request){
		$this->logActivity(['module' => 'qualification_skill','module_id' => $qualification_skill->id,'activity' => 'deleted']);

        $qualification_skill->delete();
        
        return response()->json(['message' => trans('messages.qualification').' '.trans('messages.skill').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>