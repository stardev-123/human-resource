<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\PermissionRequest;
use App\Permission;
use Entrust;

Class PermissionController extends Controller{
    use BasicController;

	public function index(){
        $permissions = \DB::table('permissions')->orderBy('category')->get();
        $permission_role = \DB::table('permission_role')
        	->select(\DB::raw('CONCAT(role_id,"-",permission_id) AS detail'))
        	->pluck('detail')->all();
        $category = null;

		return view('permission.permission',compact('permissions','permission_role','category'));
		
		$table_data['permission-table'] = array(
            'source' => 'permission',
            'title' => trans('messages.permission').' '.trans('messages.list'),
            'id' => 'permission_table',
			'data' => array(
                trans('messages.option'),
                trans('messages.name'),
                trans('messages.category'),
                trans('messages.description')
        		)
			);

		$assets = ['datatable'];

		return view('permission.index',compact('table_data','assets'));
	}

	public function show(){
	}

	public function permission(){
        $permissions = \DB::table('permissions')->orderBy('category')->get();
        $permission_role = \DB::table('permission_role')
        	->select(\DB::raw('CONCAT(role_id,"-",permission_id) AS detail'))
        	->pluck('detail')->all();
        $category = null;

		return view('permission.permission',compact('permissions','permission_role','category'));
	}

	public function savePermission(Request $request){

		$input = $request->all();
		$permissions = ($request->input('permission')) ? : [];
		foreach($permissions as $key => $permission)
			foreach($permission as $k => $perm)
				$insert[] = array('permission_id' => $k,'role_id' => $key);

        $permissions = \DB::table('permissions')->get();
        \DB::table('permission_role')->truncate();
        foreach($permissions as $permission)
            $insert[] = array('permission_id' => $permission->id,'role_id' => 1);
        \DB::table('permission_role')->insert($insert);

		$this->logActivity(['module' => 'permission','activity' => 'updated']);

        $response = ['message' => trans('messages.permission').' '.trans('messages.updated'), 'status' => 'success']; 
        $response = $this->getSetupGuide($response,'permission');
        return response()->json($response);
	}

	public function create(){
		return view('permission.create');
	}

	public function lists(){
		$permissions = Permission::all();
        $rows = array();

        foreach($permissions as $permission){
            $rows[] = array(
                ((!$permission->is_default) ? '<div class="btn-group btn-group-xs">'.
                '<a href="#" data-href="/permission/'.$permission->id.'/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a>'.
                delete_form(['permission.destroy',$permission->id]).
                '</div>' : ''),
                toWord($permission->name).(($permission->is_default) ? (' <span class="label label-danger">'.trans('messages.default').'</span>') : ''),
                toWord($permission->category),
                $permission->description
                );
        }
        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function edit(Permission $permission){
		if($permission->is_default)
            return view('global.error',['message' => trans('messages.permission_denied')]);

		return view('permission.edit',compact('permission'));
	}

	public function store(PermissionRequest $request,Permission $permission){	

		$db_permission = Permission::whereName(createSlug($request->input('name')))->count();
		if($db_permission)
        	return response()->json(['message' => trans('validation.unique',['attribute' => $request->input('name').' '.trans('messages.permission')]), 'status' => 'error']);

		$permission->name = createSlug($request->input('name'));
		$permission->category = $request->input('category');
		$permission->description = $request->input('description');
		$permission->save();

		$this->logActivity(['module' => 'permission','module_id' => $permission->id,'activity' => 'added']);

        return response()->json(['message' => trans('messages.permission').' '.trans('messages.added'), 'status' => 'success']);
	}

	public function update(PermissionRequest $request, Permission $permission){
		if($permission->is_default)
        	return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		$db_permission = Permission::whereName(createSlug($request->input('name')))->where('id','!=',$permission->id)->count();
		if($db_permission)
        	return response()->json(['message' => trans('validation.unique',['attribute' => $request->input('name').' '.trans('messages.permission')]), 'status' => 'error']);
		
		$permission->name = createSlug($request->input('name'));
		$permission->category = $request->input('category');
		$permission->description = $request->input('description');
		$permission->save();

		$this->logActivity(['module' => 'permission','module_id' => $permission->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.permission').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(Permission $permission,Request $request){

		if($permission->is_default)
        	return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		$this->logActivity(['module' => 'permission','module_id' => $permission->id,'activity' => 'deleted']);

        $permission->delete();

        return response()->json(['message' => trans('messages.permission').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>