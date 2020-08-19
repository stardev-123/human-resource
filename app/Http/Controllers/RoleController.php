<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use App\Role;
use Entrust;

Class RoleController extends Controller{
    use BasicController;

	public function index(){
		$table_data['role-table'] = array(
            'source' => 'role',
            'title' => trans('messages.role').' '.trans('messages.list'),
            'id' => 'role_table',
			'data' => array(
                trans('messages.option'),
                trans('messages.name'),
                trans('messages.description')
        		)
			);

		$assets = ['datatable'];

		return view('role.index',compact('table_data','assets'));
	}

	public function show(){
	}

	public function create(){
		return view('role.create');
	}

	public function lists(){

		if(defaultRole())
			$roles = Role::all();
		else
			$roles = Role::whereIsHidden(0)->get();
        $rows = array();

        foreach($roles as $role){
            $rows[] = array(
                '<div class="btn-group btn-group-xs">'.
                '<a href="#" data-href="/role/'.$role->id.'/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a>'.
                delete_form(['role.destroy',$role->id]).
                '</div>',
                ucfirst($role->name).(($role->is_default) ? (' <span class="label label-danger">'.trans('messages.default_user_role').'</span>') : '').(($role->is_hidden) ? (' <span class="label label-success">'.trans('messages.default').'</span>') : ''),
                $role->description
                );
        }
        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function edit(Role $role){
		if($role->is_hidden)
            return view('global.error',['message' => trans('messages.permission_denied')]);

		return view('role.edit',compact('role'));
	}

	public function store(RoleRequest $request,Role $role){	

		$db_role = Role::whereName(createSlug($request->input('name')))->count();
		if($db_role)
        	return response()->json(['message' => trans('validation.unique',['attribute' => $request->input('name').' '.trans('messages.role')]), 'status' => 'error']);

		$data = $request->all();
		$data['name'] = strtolower($request->input('name'));
		$role->fill($data);

		if($request->input('is_default')){
			Role::whereIsHidden(0)->where('id','!=',$role->id)->update(['is_default' => 0]);
			$role->is_default = 1;
		}

		$role->save();

		$this->logActivity(['module' => 'role','module_id' => $role->id,'activity' => 'added']);

        $response = ['message' => trans('messages.role').' '.trans('messages.added'), 'status' => 'success'];
        $response = $this->getSetupGuide($response,'role');
        return response()->json($response);
	}

	public function update(RoleRequest $request, Role $role){

		$db_role = Role::whereName(createSlug($request->input('name')))->where('id','!=',$role->id)->count();
		if($db_role)
        	return response()->json(['message' => trans('validation.unique',['attribute' => $request->input('name').' '.trans('messages.role')]), 'status' => 'error']);

		$data = $request->all();
		$data['name'] = strtolower($request->input('name'));
		$role->fill($data);
		
		if($request->input('is_default')){
			Role::whereIsHidden(0)->where('id','!=',$role->id)->update(['is_default' => 0]);
			$role->is_default = 1;
		}
		$role->save();

		$this->logActivity(['module' => 'role','module_id' => $role->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.role').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(Role $role,Request $request){

		if($role->is_hidden || $role->is_default)
        	return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		$this->logActivity(['module' => 'role','module_id' => $role->id,'activity' => 'deleted']);

        $role->delete();

        return response()->json(['message' => trans('messages.role').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>