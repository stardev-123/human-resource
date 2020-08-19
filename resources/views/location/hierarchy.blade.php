@foreach(\App\Location::whereNull('top_location_id')->get() as $location)
	<h4>{!! $location->name !!}</h4>
	{!! createLineTreeView($tree,$location->id) !!}
@endforeach