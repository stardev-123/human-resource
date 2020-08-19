<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\DepartmentRequest;
use Entrust;
use App\Department;

Class DepartmentController extends Controller{
    use BasicController;

	protected $form = 'department-form';

	public function index(Department $department){

		if(!Entrust::can('list-department'))
			return redirect('/home')->withErrors(trans('messages.permission_denied'));

		$data = array(
	        		trans('messages.option'),
	        		trans('messages.department'),
	        		trans('messages.description'),
	        		trans('messages.designation'),
	        		trans('messages.total').' '.trans('messages.user')
        		);

		$data = putCustomHeads($this->form, $data);

		$table_data['department-table'] = array(
				'source' => 'department',
				'title' => trans('messages.department').' '.trans('messages.list'),
				'id' => 'department_table',
				'data' => $data
			);

		$assets = ['datatable'];
		$menu = 'department';
		return view('department.index',compact('table_data','assets','menu'));
	}

	public function lists(Request $request){
		if(!Entrust::can('list-department'))
			return;

		if(defaultRole())
			$departments = Department::all();
		else
			$departments = Department::whereIsHidden(0)->get();
		
        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();

        foreach($departments as $department){

        	$total_user = 0;
        	$designation_list = '<ol>';
        	foreach($department->Designation as $designation){
        		$designation_list .= '<li>'.$designation->name.'</li>';
        		$total_user += $designation->hasMany('\App\Profile')->count();
        	}
        	$designation_list .= '</ol>';

			$row = array(
				'<div class="btn-group btn-group-xs">'.
				(Entrust::can('edit-department') ? '<a href="#" data-href="/department/'.$department->id.'/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a> ' : '').
				(Entrust::can('delete-department') ? delete_form(['department.destroy',$department->id]) : '').
				'</div>',
				$department->name.' '.(($department->is_hidden) ? '<span class="label label-danger">'.trans('messages.default').'</span>' : ''),
				$department->description,
				$designation_list,
				$total_user
				);
			$id = $department->id;

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

		if(!Entrust::can('create-department'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        return view('department.create');
	}

	public function edit(Department $department){

		if(!Entrust::can('edit-department'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

		$custom_field_values = getCustomFieldValues($this->form,$department->id);
		return view('department.edit',compact('department','custom_field_values'));
	}

	public function store(DepartmentRequest $request, Department $department){	

		if(!Entrust::can('create-department'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

		$data = $request->all();
		$department->fill($data)->save();
		storeCustomField($this->form,$department->id, $data);

		$this->logActivity(['module' => 'department','module_id' => $department->id,'activity' => 'added']);

        $response = ['message' => trans('messages.department').' '.trans('messages.added'), 'status' => 'success']; 
        $response = $this->getSetupGuide($response,'department');
        return response()->json($response);
	}

	public function update(DepartmentRequest $request, Department $department){

		if(!Entrust::can('edit-department'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

		$data = $request->all();
		$department->fill($data)->save();

		$this->logActivity(['module' => 'department','module_id' => $department->id,'activity' => 'updated']);

		updateCustomField($this->form,$department->id, $data);
		
        return response()->json(['message' => trans('messages.department').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(Department $department,Request $request){
		if(!Entrust::can('delete-department') || $department->is_hidden == 1)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		$this->logActivity(['module' => 'department','module_id' => $department->id,'activity' => 'deleted']);

		deleteCustomField($this->form, $department->id);
        
        $department->delete();
        
        return response()->json(['message' => trans('messages.department').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>