<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\DesignationRequest;
use Entrust;
use App\Designation;

Class DesignationController extends Controller{
    use BasicController;

	protected $form = 'designation-form';

	public function index(Designation $designation){
		if(!Entrust::can('list-designation'))
			return redirect('/home')->withErrors(trans('messages.permission_denied'));

		$departments = \App\Department::all()->pluck('name','id')->all();

		$top_designations = Designation::whereIn('id',getDesignation(\Auth::user(),1))->get()->pluck('designation_with_department','id')->all();

        $data = array(
        		trans('messages.option'),
        		trans('messages.designation'),
        		trans('messages.department'),
        		trans('messages.top').' '.trans('messages.designation'));

        $data = putCustomHeads($this->form, $data);
        $table_data['designation-table'] = array(
			'source' => 'designation',
			'title' => trans('messages.designation').' '.trans('messages.list'),
			'id' => 'designation_table',
			'data' => $data
		);
		$assets = ['datatable'];
		$menu = 'designation';

		return view('designation.index',compact('table_data','assets','menu','top_designations','departments'));
	}

	public function hierarchy(Request $request){

        $tree = array();
        $designations = \App\Designation::all();
        foreach ($designations as $designation){
            $tree[$designation->id] = array(
                'parent_id' => $designation->top_designation_id,
                'name' => $designation->designation_with_department
            );
        }

        return view('designation.hierarchy',compact('tree'))->render();
	}

	public function lists(Request $request){
		if(!Entrust::can('list-designation'))
			return;

		if(defaultRole())
			$designations = Designation::all();
		elseif(Entrust::can('manage-all-designation'))
			$designations = Designation::whereIsHidden(0)->get();
		elseif(Entrust::can('manage-subordinate-designation')){
			$child_designations = childDesignation(\Auth::user()->Profile->designation_id,1);
			$designations = Designation::whereIn('id',$child_designations)->get();
		} else
			$designations = [];

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();

        foreach ($designations as $designation){
			$row = array(
				'<div class="btn-group btn-group-xs">'.
				(Entrust::can('edit-designation') ? '<a href="#" data-href="/designation/'.$designation->id.'/edit" class="btn btn-default btn-xs" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a> ' : '').
				(Entrust::can('delete-designation') ? delete_form(['designation.destroy',$designation->id]) : '').
				'</div>',
				$designation->name.' '.(($designation->is_hidden) ? '<span class="label label-danger">'.trans('messages.default').'</span>' : '').(($designation->is_default) ? '<span class="label label-warning">'.trans('messages.user').' '.trans('messages.default').'</span>' : ''),
				$designation->Department->name,
				($designation->top_designation_id) ? $designation->Parent->designation_with_department : '<i class="fa fa-times"></i>'
			);
			$id = $designation->id;

			foreach($col_ids as $col_id)
				array_push($row,isset($values[$id][$col_id]) ? $values[$id][$col_id] : '');
	    	$rows[] = $row;
    	}

        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function show(){
	}

	public function create(){
	}

	public function edit(Designation $designation){

        if(!$this->designationAccessible($designation) || !Entrust::can('edit-designation'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

		$child_designations = childDesignation($designation->id,1);
		array_push($child_designations, \Auth::user()->Profile->designation_id);

		// array_diff is used to remove child designations from the lists.

		if(Entrust::can('manage-all-designation'))
			$top_designations = array_diff(Designation::where('id','!=',$designation->id)->get()->pluck('designation_with_department','id')->all(), $child_designations);
		elseif(Entrust::can('manage-subordinate-designation'))
			$top_designations = array_diff(Designation::where('id','!=',$designation->id)->whereIn('id',$child_designations)->get()->pluck('designation_with_department','id')->all(), $child_designations);
		else
			$top_designations = [];

		$departments = \App\Department::all()->pluck('name','id')->all();

		$custom_field_values = getCustomFieldValues($this->form,$designation->id);

		return view('designation.edit',compact('designation','top_designations','custom_field_values','departments'));
	}

	public function store(DesignationRequest $request, Designation $designation){	

		if(!Entrust::can('create-designation'))
	       	return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

		$data = $request->all();

		$data['top_designation_id'] = ($request->input('top_designation_id')) ? : null;
		$designation->fill($data)->save();

        if($request->has('is_default')){
        	\App\Designation::whereNotNull('id')->update(['is_default' => 0]);
        	$designation->is_default = 1;
        	$designation->save();
        }

		storeCustomField($this->form,$designation->id, $data);

		$this->logActivity(['module' => 'designation','module_id' => $designation->id,'activity' => 'added']);

    	$new_data = array('value' => $designation->designation_with_department,'id' => $designation->id,'field' => 'top_designation_id');
        $response = ['message' => trans('messages.designation').' '.trans('messages.added'), 'status' => 'success','new_data' => $new_data]; 
        $response = $this->getSetupGuide($response,'designation');
        return response()->json($response);
	}

	public function update(DesignationRequest $request, Designation $designation){

		if(!Entrust::can('edit-designation') || !$this->designationAccessible($designation))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $data = $request->all();

		$data['top_designation_id'] = ($request->input('top_designation_id')) ? : null;

		$child_designations = childDesignation($designation->id,1);

		if($data['top_designation_id'] != null && in_array($data['top_designation_id'],$child_designations))
            return response()->json(['message' => trans('messages.top_designation_cannot_become_child'), 'status' => 'error']);

		$designation->fill($data)->save();

        if($request->has('is_default')){
        	\App\Designation::whereNotNull('id')->update(['is_default' => 0]);
        	$designation->is_default = 1;
        	$designation->save();
        }
        
		$this->logActivity(['module' => 'designation','module_id' => $designation->id,'activity' => 'updated']);

		updateCustomField($this->form,$designation->id, $data);

        return response()->json(['message' => trans('messages.designation').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(Designation $designation,Request $request){
		if(!Entrust::can('delete-designation') || !$this->designationAccessible($designation) || $designation->is_hidden == 1)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		$this->logActivity(['module' => 'designation','module_id' => $designation->id,'activity' => 'deleted']);

		deleteCustomField($this->form, $designation->id);
		
        $designation->delete();
        return response()->json(['message' => trans('messages.designation').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>