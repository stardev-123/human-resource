<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\IpFilterRequest;
use App\IpFilter;

Class IpFilterController extends Controller{
    use BasicController;

	public function __construct()
	{
		$this->middleware('feature_available:enable_ip_filter');
	}

	public function index(){

		$table_data['ip-filter-table'] = array(
            'source' => 'ip-filter',
            'title' => 'IP '.trans('messages.filter').' '.trans('messages.list'),
            'id' => 'ip_filter_table',
			'data' => array(
                trans('messages.option'),
                trans('messages.start'),
                trans('messages.end')
        		)
			);

		$assets = ['datatable'];

		return view('ip_filter.index',compact('table_data','assets'));
	}

	public function lists(){
		$ip_filters = IpFilter::all();
        $rows = array();

        foreach($ip_filters as $ip_filter){
            $rows[] = array(
                '<div class="btn-group btn-group-xs">'.
                '<a href="#" data-href="/ip-filter/'.$ip_filter->id.'/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a>'.
                delete_form(['ip-filter.destroy',$ip_filter->id]).
                '</div>',
                $ip_filter->start,
                $ip_filter->end
                );
        }
        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function show(){
	}

	public function create(){
		return view('ip_filter.create');
	}

	public function edit(IpFilter $ip_filter){
		return view('ip_filter.edit',compact('ip_filter'));
	}

	public function validateIp($start_ip,$end_ip,$ip_filter = null){

		if($end_ip && $start_ip > $end_ip){
			$response = ['message' => trans('messages.invalid_ip_range'), 'status' => 'error']; 
			return $response;
		}

		$start_ip_same = 0;
		$start_ip_in_range = 0;
		$end_ip_in_range = 0;
		$other_ip_in_range = 0;

		if(!$ip_filter)
			$ips = IpFilter::all();
		else
			$ips = IpFilter::where('id','!=',$ip_filter->id)->get();
		foreach($ips as $ip){
			$all_start_ip = ip2long($ip->start);
			$all_end_ip = ($ip->end) ? ip2long($ip->end) : null;

			if($all_start_ip == $start_ip)
				$start_ip_same++;
			elseif($end_ip && !$all_end_ip && $start_ip <= $all_start_ip && $end_ip >= $all_start_ip)
				$other_ip_in_range++;
			elseif($all_end_ip && $start_ip >= $all_start_ip && $start_ip <= $all_end_ip)
				$start_ip_in_range++;
			elseif($end_ip && $end_ip >= $all_start_ip && $end_ip <= $all_end_ip)
				$end_ip_in_range++;
			elseif($end_ip && $start_ip < $all_start_ip && $end_ip > $all_end_ip)
				$other_ip_in_range++;
		}

		if($start_ip_same)
			$response = ['message' => trans('messages.start_ip_same'), 'status' => 'error']; 
		elseif($start_ip_in_range)
			$response = ['message' => trans('messages.start_ip_in_range'), 'status' => 'error']; 
		elseif($end_ip_in_range)
			$response = ['message' => trans('messages.end_ip_in_range'), 'status' => 'error']; 
		elseif($other_ip_in_range)
			$response = ['message' => trans('messages.other_ip_in_range'), 'status' => 'error']; 
		else
			$response = ['status' => 'success'];

		return $response;
	}

	public function store(IpFilterRequest $request, IpFilter $ip_filter){

		if(!getMode())
			return response()->json(['message' => trans('messages.disable_message'),'status' => 'error']);

		$start_ip = ip2long($request->input('start'));
		$end_ip = ($request->input('end')) ? ip2long($request->input('end')) : null;

		$response = $this->validateIp($start_ip,$end_ip);

		if($response['status'] == 'error')
			return response()->json($response);

		$ip_filter->fill($request->all())->save();

		$this->logActivity(['module' => 'ip_filter','module_id' => $ip_filter->id,'activity' => 'added']);

        return response()->json(['message' => 'Ip'.' '.trans('messages.filter').' '.trans('messages.added'), 'status' => 'success']);
	}

	public function update(IpFilterRequest $request, IpFilter $ip_filter){

		if(!getMode())
			return response()->json(['message' => trans('messages.disable_message'),'status' => 'error']);

		$start_ip = ip2long($request->input('start'));
		$end_ip = ($request->input('end')) ? ip2long($request->input('end')) : null;

		$response = $this->validateIp($start_ip,$end_ip,$ip_filter);

		if($response['status'] == 'error')
			return response()->json($response);

		$ip_filter->fill($request->all())->save();

		$this->logActivity(['module' => 'ip_filter','module_id' => $ip_filter->id,'activity' => 'updated']);

        return response()->json(['message' => 'Ip'.' '.trans('messages.filter').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(IpFilter $ip_filter,Request $request){

		if(!getMode())
			return response()->json(['message' => trans('messages.disable_message'),'status' => 'error']);
		
		$this->logActivity(['module' => 'ip_filter','module_id' => $ip_filter->id,'activity' => 'deleted']);

        $ip_filter->delete();

        return response()->json(['message' => 'Ip'.' '.trans('messages.filter').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>