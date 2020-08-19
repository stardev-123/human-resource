<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\AwardCategoryRequest;
use Entrust;
use App\AwardCategory;

Class AwardCategoryController extends Controller{
    use BasicController;

	public function lists(Request $request){
		$award_categories = AwardCategory::all();
		return view('award_category.list',compact('award_categories'))->render();
	}

	public function show(){
	}

	public function create(){
		return view('award_category.create');
	}

	public function edit(AwardCategory $award_category){
		return view('award_category.edit',compact('award_category'));
	}

	public function store(AwardCategoryRequest $request, AwardCategory $award_category){	

		$data = $request->all();
		$award_category->fill($data)->save();

		$this->logActivity(['module' => 'award_category','module_id' => $award_category->id,'activity' => 'added']);

    	$new_data = array('value' => $award_category->name,'id' => $award_category->id,'field' => 'award_category_id');
        $response = ['message' => trans('messages.award').' '.trans('messages.category').' '.trans('messages.added'), 'status' => 'success','new_data' => $new_data]; 
        return response()->json($response);
	}

	public function update(AwardCategoryRequest $request, AwardCategory $award_category){

		$data = $request->all();
		$award_category->fill($data)->save();

		$this->logActivity(['module' => 'award_category','module_id' => $award_category->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.award').' '.trans('messages.category').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(AwardCategory $award_category,Request $request){
		$this->logActivity(['module' => 'award_category','module_id' => $award_category->id,'activity' => 'deleted']);

        $award_category->delete();
        
        return response()->json(['message' => trans('messages.award').' '.trans('messages.category').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>