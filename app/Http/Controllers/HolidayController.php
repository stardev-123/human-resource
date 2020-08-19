<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\HolidayRequest;
use Entrust;
use App\Holiday;

Class HolidayController extends Controller{
    use BasicController;

	protected $form = 'holiday-form';

	public function index(Holiday $holiday){

		$data = array(
	        		trans('messages.option'),
	        		trans('messages.date'),
	        		trans('messages.description')
        		);

		$data = putCustomHeads($this->form, $data);

		$table_data['holiday-table'] = array(
				'source' => 'holiday',
				'title' => trans('messages.holiday').' '.trans('messages.list'),
				'id' => 'holiday_table',
				'data' => $data,
				'form' => 'holiday-filter-form'
			);

		$months = translateList('month');
		$years = getYears(date('Y')-5,date('Y'));
		$assets = ['datatable','graph'];
		$menu = 'holiday';
		return view('holiday.index',compact('table_data','assets','menu','months','years'));
	}

	public function lists(Request $request){

		$query = Holiday::whereNotNull('id');

		$months = $request->has('month') ? $request->input('month') : config('lists.month');
		$year = $request->has('year') ? $request->input('year') : date('Y');

		$i = 0;
		foreach($months as $month){
			if($i)
				$query->orWhere(function($query1) use($year,$month){
					$query1->where(\DB::raw('MONTH(date)'), [getMonthNumber($month)])->where(\DB::raw('YEAR(date)'), [$year]);
				});
			else 
				$query->Where(function($query1) use($year,$month){
					$query1->where(\DB::raw('MONTH(date)'), [getMonthNumber($month)])->where(\DB::raw('YEAR(date)'), [$year]);
				});
			$i++;
		}

		$holidays = $query->get();
		
        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();

        foreach($holidays as $holiday){

			$row = array(
				'<div class="btn-group btn-group-xs">'.
				'<a href="#" data-href="/holiday/'.$holiday->id.'/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a> '.
				delete_form(['holiday.destroy',$holiday->id]).
				'</div>',
				showDate($holiday->date),
				$holiday->description
				);
			$id = $holiday->id;

			foreach($col_ids as $col_id)
				array_push($row,isset($values[$id][$col_id]) ? $values[$id][$col_id] : '');
			$rows[] = $row;
        }

        $month_names = array();
        $month_holiday = array();
        $month_holiday_count = array();

        foreach($months as $month){
        	$month_names[] = ucfirst($month);
        	$month_holiday[ucfirst($month)] = 0;
        }
    	foreach($holidays as $holiday)
    		$month_holiday[date('F',strtotime($holiday->date))]++;

    	foreach($month_holiday as $key => $value)
    		$month_holiday_count[] = $value;

		$record_height = 50;
		$extra_height = 100;
		$height = $record_height*count($month_names) + $extra_height;

        $list['aaData'] = $rows;
        $list['graph'] = [
            'holiday' => [
            	'title_text' => trans('messages.holiday').' '.trans('messages.statistics').' '.$year,
            	'name' => trans('messages.month'),
                'ydata' => $month_names,
                'xdata' => $month_holiday_count,
                'legend' => [trans('messages.month')],
                'title' => trans('messages.holiday'),
                'height' => $height
            ]
        ];
        return json_encode($list);
	}

	public function show(){
	}

	public function create(){
	}

	public function edit(Holiday $holiday){
		$custom_field_values = getCustomFieldValues($this->form,$holiday->id);
		return view('holiday.edit',compact('holiday','custom_field_values'));
	}

	public function store(HolidayRequest $request, Holiday $holiday){	

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

		$dates = explode(',',$request->input('date'));
		$data = $request->all();
		foreach($dates as $date){
			$holiday_exists = Holiday::where('date','=',$date)->count();
			if(!$holiday_exists){
				$holiday = new Holiday;
				$holiday->date = $date;
				$holiday->description = $request->input('description');
				$holiday->save();
				storeCustomField($this->form,$holiday->id, $data);
				$this->logActivity(['module' => 'holiday','module_id' => $holiday->id,'activity' => 'added']);
			}
		}

        return response()->json(['message' => trans('messages.holiday').' '.trans('messages.added'), 'status' => 'success']);
	}

	public function update(HolidayRequest $request, Holiday $holiday){

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

		$data = $request->all();
		$holiday->fill($data)->save();

		$this->logActivity(['module' => 'holiday','module_id' => $holiday->id,'activity' => 'updated']);

		updateCustomField($this->form,$holiday->id, $data);
		
        return response()->json(['message' => trans('messages.holiday').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(Holiday $holiday,Request $request){
		$this->logActivity(['module' => 'holiday','module_id' => $holiday->id,'activity' => 'deleted']);

		deleteCustomField($this->form, $holiday->id);
        
        $holiday->delete();
        
        return response()->json(['message' => trans('messages.holiday').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>