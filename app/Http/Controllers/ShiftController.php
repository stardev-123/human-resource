<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\ShiftRequest;
use Entrust;
use App\Shift;

Class ShiftController extends Controller{
    use BasicController;

	protected $form = 'shift-form';

	public function index(Shift $shift){
		$data = array(
	        		trans('messages.option'),
	        		trans('messages.shift'),
	        		trans('messages.default'),
	        		trans('messages.description'),
	        		trans('messages.monday'),
	        		trans('messages.tuesday'),
	        		trans('messages.wednesday'),
	        		trans('messages.thursday'),
	        		trans('messages.friday'),
	        		trans('messages.saturday'),
	        		trans('messages.sunday')
        		);

		$data = putCustomHeads($this->form, $data);

		$table_data['shift-table'] = array(
				'source' => 'shift',
				'title' => trans('messages.shift').' '.trans('messages.list'),
				'id' => 'shift_table',
				'data' => $data
			);

		$assets = ['timepicker','datatable'];
		$menu = 'shift';
		return view('shift.index',compact('table_data','assets','menu'));
	}

	public function lists(Request $request){
		$shifts = Shift::all();
        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();

        foreach($shifts as $shift){
			$row = array(
				'<div class="btn-group btn-group-xs">'.
				'<a href="#" data-href="/shift/'.$shift->id.'/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a> '.
				delete_form(['shift.destroy',$shift->id]).
				'</div>',
				$shift->name,
				(($shift->is_default) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'),
				$shift->description
				);

			foreach($shift->ShiftDetail as $shift_detail)
				array_push($row,($shift_detail->in_time == $shift_detail->out_time) ? '-' : ((($shift_detail->overnight) ? '<strong>(O)</strong>' : '').' '.showTime($shift_detail->in_time).' '.trans('messages.to').' '.showTime($shift_detail->out_time)));

			$id = $shift->id;
			foreach($col_ids as $col_id)
				array_push($row,isset($values[$id][$col_id]) ? $values[$id][$col_id] : '');
			$rows[] = $row;
        }
        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function create(){

	}

	public function edit(Shift $shift){
		$shift_details = $shift->ShiftDetail;

		$week = array();
		foreach($shift_details as $shift_detail){
			$week['in_time'][$shift_detail->day] = date('h:i a',strtotime($shift_detail->in_time));
			$week['out_time'][$shift_detail->day] = date('h:i a',strtotime($shift_detail->out_time));
		}
		$custom_field_values = getCustomFieldValues($this->form,$shift->id);
		return view('shift.edit',compact('shift','custom_field_values','week'));
	}

	public function show(Shift $shift){

	}

	public function store(ShiftRequest $request, Shift $shift){

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

		$shift->name = $request->input('name');
		$shift->description = $request->input('description');
		$week = $request->input('week');
		$overnight = $request->input('overnight');

		$error = array();
		foreach(config('lists.week') as $day){
			if(strtotime($week['in_time'][$day]) > strtotime($week['out_time'][$day]) && $overnight[$day] != 1)
				$error[] = trans('messages.'.$day);
		}	

		if(count($error))
	        return response()->json(['message' => implode(',',$error).' '.trans('messages.out_time_not_less_than_in_time'), 'status' => 'error']);

		if($request->input('is_default')){
			Shift::where('id','!=',$shift->id)->update(['is_default' => 0]);
			$shift->is_default = 1;
		}

		if(Shift::count() == 0)
			$shift->is_default = 1;
		
		$shift->save();

		foreach(config('lists.week') as $day){
			$shift_detail = new \App\ShiftDetail;
			$shift_detail->shift_id = $shift->id;
			$shift_detail->overnight = isset($overnight[$day]) ? 1 : 0;
			$shift_detail->day = $day;
			$shift_detail->in_time = ($week['in_time'][$day]) ? date('H:i:s',strtotime($week['in_time'][$day])) : '00:00:00';
			$shift_detail->out_time = ($week['out_time'][$day]) ? date('H:i:s',strtotime($week['out_time'][$day])) : '00:00:00';
			$shift_detail->save();
		}
		$data = $request->all();
		storeCustomField($this->form,$shift->id, $data);

		$this->logActivity(['module' => 'shift','module_id' => $shift->id,'activity' => 'added']);
        return response()->json(['message' => trans('messages.shift').' '.trans('messages.added'), 'status' => 'success']);
	}

	public function update(ShiftRequest $request, Shift $shift){
		
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

		$week = $request->input('week');
		$overnight = $request->input('overnight');

		$error = array();
		foreach(config('lists.week') as $day)
			if(strtotime($week['in_time'][$day]) > strtotime($week['out_time'][$day]) && $overnight[$day] != 1)
				$error[] = trans('messages.'.$day);

		if(count($error))
	        return response()->json(['message' => implode(',',$error).' '.trans('messages.out_time_not_less_than_in_time'), 'status' => 'error']);

		$shift->fill([
			'name' => $request->input('name'),
			'description' => $request->input('description')
			])->save();

		if($request->input('is_default')){
			Shift::where('id','!=',$shift->id)->update(['is_default' => 0]);
			$shift->is_default = 1;
			$shift->save();
		} else 

		foreach(config('lists.week') as $day){
			\App\ShiftDetail::whereShiftId($shift->id)
				->where('day','=',$day)
				->update(['in_time' => ($week['in_time'][$day]) ? date('H:i:s',strtotime($week['in_time'][$day])) : '00:00:00',
					'out_time' => ($week['out_time'][$day]) ? date('H:i:s',strtotime($week['out_time'][$day])) : '00:00:00', 'overnight' => isset($overnight[$day]) ? 1 : 0]);
		}

		$data = $request->all();
		updateCustomField($this->form,$shift->id, $data);

		$this->logActivity(['module' => 'shift','module_id' => $shift->id,'activity' => 'updated']);

	    return response()->json(['message' => trans('messages.shift').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(Request $request, Shift $shift){
		if($shift->is_default)
	        return response()->json(['message' => trans('messages.default_shift_cannot_delete'), 'status' => 'error']);

		deleteCustomField($this->form, $shift->id);
        $this->logActivity(['module' => 'shift','module_id' => $shift->id,'activity' => 'deleted']);

        $shift->delete();
        return response()->json(['message' => trans('messages.shift').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}