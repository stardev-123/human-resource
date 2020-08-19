@foreach(\App\Designation::whereNull('top_designation_id')->get() as $designation)
	<h4>{!! $designation->designation_with_department !!}</h4>
	{!! createLineTreeView($tree,$designation->id) !!}
@endforeach