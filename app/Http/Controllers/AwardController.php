<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\AwardRequest;
use Entrust;
use App\Award;

Class AwardController extends Controller{
    use BasicController;

	protected $form = 'award-form';

	public function isAccessible($award){
		$is_accessible = Award::whereId($award->id)->where(function($qry){
			$qry->whereIn('user_id',getAccessibleUserId(\Auth::user()->id,1))->orWhere(function($query1){
				$query1->where(function($query2){
					$query2->whereHas('user',function($query3){
						$query3->where('user_id','=',\Auth::user()->id);
					});
				});
			});
		})->count();

		if($is_accessible)
			return 1;
		else
			return 0;
	}

	public function index(){
		if(!Entrust::can('list-award'))
			return redirect('/home')->withErrors(trans('messages.permission_denied'));

		$data = array(
	        		trans('messages.option'),
	        		trans('messages.award').' '.trans('messages.category'),
	        		trans('messages.duration'),
	        		trans('messages.date_of').' '.trans('messages.award'),
	        		trans('messages.user'),
	        		trans('messages.created_at')
        		);

		$data = putCustomHeads($this->form, $data);

		$table_data['award-table'] = array(
				'source' => 'award',
				'title' => trans('messages.award').' '.trans('messages.list'),
				'id' => 'award_table',
				'data' => $data,
				'form' => 'award-filter-form'
			);

		$accessible_users = getAccessibleUserList();
		$months = translateList('month');
		$years = getYears(date('Y')-5,date('Y'));
		$award_categories = \App\AwardCategory::all()->pluck('name','id')->all();

		$assets = ['datatable','summernote','graph'];
		$menu = 'award';
		return view('award.index',compact('table_data','assets','menu','accessible_users','months','years','award_categories'));
	}

	public function awardDuration($award){
		if($award->duration == 'monthly')
			return trans('messages.'.$award->month).' '.$award->year;
		elseif($award->duration == 'yearly')
			return $award->year;
		elseif($award->duration == 'period')
			return showDate($award->from_date).' '.trans('messages.to').' '.showDate($award->to_date);
	}

	public function lists(Request $request){
		if(!Entrust::can('list-award'))
			return;

		$query = Award::where(function($qry){
			$qry->whereIn('user_id',getAccessibleUserId(\Auth::user()->id,1))->orWhere(function($query1){
				$query1->where(function($query2){
					$query2->whereHas('user',function($query3){
						$query3->where('user_id','=',\Auth::user()->id);
					});
				});
			});
		});

		if($request->has('user_id'))
			$query->whereHas('user',function($query10) use($request){
				$query10->whereIn('user_id',$request->input('user_id'));
			});

		if($request->has('award_category_id'))
			$query->whereIn('award_category_id',$request->input('award_category_id'));

        if($request->has('date_of_award_start') && $request->has('date_of_award_end'))
        	$query->whereBetween('date_of_award',[$request->input('date_of_award_start'),$request->input('date_of_award_end')]);

        if($request->has('created_at_start') && $request->has('created_at_end'))
        	$query->whereBetween('created_at',[$request->input('created_at_start').' 00:00:00',$request->input('created_at_end').' 23:59:59']);

        $awards = $query->get();
        
        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();

        foreach($awards as $award){
			$award_users = '<ol>';
			foreach($award->User as $user)
				$award_users .= '<li>'.$user->name_with_designation_and_department.'</li>';
			$award_users .= '</ol>';

			$row = array(
				'<div class="btn-group btn-group-xs">'.
				'<a href="#" data-href="/award/'.$award->id.'" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="'.trans('messages.view').'"></i></a> '.
				((Entrust::can('edit-award') && $award->user_id == \Auth::user()->id) ? '<a href="#" data-href="/award/'.$award->id.'/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a> ' : '').
				((Entrust::can('delete-award') && $award->user_id == \Auth::user()->id) ? delete_form(['award.destroy',$award->id]) : '').
				'</div>',
				$award->AwardCategory->name,
				$this->awardDuration($award),
				showDate($award->date_of_award),
				$award_users,
				showDateTime($award->created_at)
				);
			$id = $award->id;

			foreach($col_ids as $col_id)
				array_push($row,isset($values[$id][$col_id]) ? $values[$id][$col_id] : '');
			$rows[] = $row;
        }

        $award_categories = array();
        $locations = array();
        $departments = array();
        foreach($awards as $award){
            foreach($award->User as $user){
	            if($user->location_name)
	                $locations[] = $user->location_name;
	            if($user->department_name)
	                $departments[] = $user->department_name;
            }
            $award_categories[] = $award->AwardCategory->name;
        }

        $list['graph']['award_location'] = getPieCharData($locations,'location-wise-award-graph');
        $list['graph']['award_department'] = getPieCharData($departments,'department-wise-award-graph');
        $list['graph']['award_category'] = getPieCharData($award_categories,'category-wise-award-graph');

        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function show(Award $award){
        if(!$this->isAccessible($award))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $custom_fields = \App\CustomField::whereForm('award-form')->get();
        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $award_duration = $this->awardDuration($award);
		$uploads = \App\Upload::whereModule('award')->whereModuleId($award->id)->whereStatus(1)->get();
		$this->updateNotification(['module' => 'award','module_id' => $award->id]);
        return view('award.show',compact('award','award_duration','custom_fields','col_ids','values','uploads'));
	}

	public function edit(Award $award){
		if(!Entrust::can('edit-award') || $award->user_id != \Auth::user()->id)
            return view('global.error',['message' => trans('messages.permission_denied')]);

		$accessible_users = getAccessibleUserList();
		$months = translateList('month');
		$years = getYears(date('Y')-5,date('Y'));
		$award_categories = \App\AwardCategory::all()->pluck('name','id')->all();

		$uploads = editUpload('award',$award->id);
		$custom_field_values = getCustomFieldValues($this->form,$award->id);

        return view('award.edit',compact('award','accessible_users','months','years','uploads','award_categories','custom_field_values'));
	}

	public function download($id){
        $upload = \App\Upload::whereUuid($id)->whereModule('award')->whereStatus(1)->first();

        if(!$upload)
            return redirect('/award')->withErrors(trans('messages.invalid_link'));

        $award = Award::find($upload->module_id);

        if(!$award)
            return redirect('/award')->withErrors(trans('messages.invalid_link'));

        if(!$this->isAccessible($award))
            return redirect('/award')->withErrors(trans('messages.permission_denied'));

        if(!\Storage::exists('attachments/'.$upload->attachments))
            return redirect('/award')->withErrors(trans('messages.file_not_found'));

        $download_path = storage_path().config('constant.storage_root').'attachments/'.$upload->attachments;

        return response()->download($download_path, $upload->user_filename);
	}

	public function store(AwardRequest $request, Award $award){
		if(!Entrust::can('create-award'))
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $upload_validation = validateUpload('award',$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

        $data = $request->all();
        $award->fill($data);
        $award->user_id = \Auth::user()->id;
        $award->month = ($request->input('duration') == 'monthly') ? $request->input('month') : null;
        $award->year = ($request->input('duration') == 'monthly' || $request->input('duration') == 'yearly') ? $request->input('year') : null;
        $award->from_date = ($request->input('duration') == 'period') ? $request->input('from_date') : null;
        $award->to_date = ($request->input('duration') == 'period') ? $request->input('to_date') : null;
        $award->save();
        $award->user()->sync(($request->input('user_id')) ? : []);
		$this->logActivity(['module' => 'award','module_id' => $award->id,'activity' => 'added']);
		storeCustomField($this->form,$award->id, $data);
        storeUpload('award',$award->id,$request);

        $notification_users = implode(',',$award->user()->pluck('user_id')->all());
        $this->sendNotification(['module' => 'award','module_id' => $award->id,'url' => '/award','user' => $notification_users]);

        return response()->json(['message' => trans('messages.award').' '.trans('messages.added'), 'status' => 'success']);
	}

	public function update(AwardRequest $request, Award $award){
		if(!Entrust::can('edit-award') || $award->user_id != \Auth::user()->id)
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);
        
        $upload_validation = updateUpload('award',$award->id,$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

        $data = $request->all();
        $award->fill($data);
        $award->month = ($request->input('duration') == 'monthly') ? $request->input('month') : null;
        $award->year = ($request->input('duration') == 'monthly' || $request->input('duration') == 'yearly') ? $request->input('year') : null;
        $award->from_date = ($request->input('duration') == 'period') ? $request->input('from_date') : null;
        $award->to_date = ($request->input('duration') == 'period') ? $request->input('to_date') : null;
        $award->save();
        $award->user()->sync(($request->input('user_id')) ? : []);

		$this->logActivity(['module' => 'award','module_id' => $award->id,'activity' => 'updated']);
		updateCustomField($this->form,$award->id, $data);

        return response()->json(['message' => trans('messages.award').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(Request $request, Award $award){
		if(!Entrust::can('delete-award') || $award->user_id != \Auth::user()->id)
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

		deleteUpload('award',$award->id);
		$this->logActivity(['module' => 'award','module_id' => $award->id,'activity' => 'deleted']);

		deleteCustomField($this->form, $award->id);
		$award->delete();
        return response()->json(['message' => trans('messages.award').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}