<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\LocationRequest;
use Entrust;
use App\Location;

Class LocationController extends Controller{
    use BasicController;

	protected $form = 'location-form';

	public function index(Location $location){
		if(!Entrust::can('list-location'))
			return redirect('/home')->withErrors(trans('messages.permission_denied'));

		$top_locations = Location::all()->pluck('name','id')->all();

        $data = array(
        		trans('messages.option'),
        		trans('messages.location'),
        		trans('messages.top').' '.trans('messages.location'),
        		trans('messages.address'),
        		trans('messages.city'),
        		trans('messages.state'),
        		trans('messages.postcode'),
        		trans('messages.country')
        		);
        $data = putCustomHeads($this->form, $data);
        $table_data['location-table'] = array(
			'source' => 'location',
			'title' => trans('messages.location').' '.trans('messages.list'),
			'id' => 'location_table',
			'data' => $data
		);
		$assets = ['datatable'];
		$menu = 'location';
		return view('location.index',compact('table_data','assets','top_locations','menu'));
	}

	public function hierarchy(Request $request){

        $tree = array();
        $locations = \App\Location::all();
        foreach ($locations as $location){
            $tree[$location->id] = array(
                'parent_id' => $location->top_location_id,
                'name' => $location->name
            );
        }

        return view('location.hierarchy',compact('tree'))->render();
	}

	public function lists(Request $request){
		if(!Entrust::can('list-location'))
			return;

		$locations = Location::all();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();

        foreach ($locations as $location){
			$row = array(
				'<div class="btn-group btn-group-xs">'.
				(Entrust::can('edit-location') ? '<a href="#" data-href="/location/'.$location->id.'/edit" class="btn btn-default btn-xs" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a> ' : '').
				(Entrust::can('delete-location') ? delete_form(['location.destroy',$location->id]) : '').
				'</div>',
				$location->name,
				($location->top_location_id) ? $location->Parent->name : '<i class="fa fa-times"></i>',
				$location->address_line_1.' '.$location->address_line_2,
				$location->city,
				$location->state,
				$location->zipcode,
				($location->country_id) ? config('country.'.$location->country_id) : ''
			);
			$id = $location->id;

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

	public function edit(Location $location){

        if(!Entrust::can('edit-location'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

		$child_locations = childLocation($location->id);
		$top_locations = array_diff(Location::where('id','!=',$location->id)->get()->pluck('name','id')->all(), $child_locations);

		$custom_field_values = getCustomFieldValues($this->form,$location->id);

		return view('location.edit',compact('location','top_locations','custom_field_values'));
	}

	public function store(LocationRequest $request, Location $location){	

		if(!Entrust::can('create-location'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

		$data = $request->all();

		$data['top_location_id'] = ($request->input('top_location_id')) ? : null;
		$location->fill($data)->save();

		storeCustomField($this->form,$location->id, $data);

		$this->logActivity(['module' => 'location','module_id' => $location->id,'activity' => 'added']);

    	$new_data = array('value' => $location->name,'id' => $location->id,'field' => 'top_location_id');
        $response = ['message' => trans('messages.location').' '.trans('messages.added'), 'status' => 'success','new_data' => $new_data]; 
        $response = $this->getSetupGuide($response,'location');
        return response()->json($response);
	}

	public function update(LocationRequest $request, Location $location){

		if(!Entrust::can('edit-location'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $data = $request->all();

		$data['top_location_id'] = ($request->input('top_location_id')) ? : null;

		$child_locations = childLocation($location->id,1);

		if($data['top_location_id'] != null && in_array($data['top_location_id'],$child_locations))
            return response()->json(['message' => trans('messages.top_location_cannot_become_child'), 'status' => 'error']);

		$location->fill($data)->save();

		$this->logActivity(['module' => 'location','module_id' => $location->id,'activity' => 'updated']);

		updateCustomField($this->form,$location->id, $data);

        return response()->json(['message' => trans('messages.location').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(Location $location,Request $request){
		if(!Entrust::can('delete-location'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		$this->logActivity(['module' => 'location','module_id' => $location->id,'activity' => 'deleted']);

		deleteCustomField($this->form, $location->id);
		
        $location->delete();
        return response()->json(['message' => trans('messages.location').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>