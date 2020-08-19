	@foreach($top_chart as $chart)
		<div class="row">
			<div class="col-md-2">
				{!! getAvatar($chart['id'],((Auth::user()->id == $chart['id']) ? 45 : 40)) !!}
			</div>
			<div class="col-md-6">
				{{ $chart['name'] }}
			</div>
			<div class="col-md-4">
				{!! getRatingStar($chart['rating']) !!} 
				{{ $chart['task'].' '.trans('messages.task')}}
			</div>
		</div>
	@endforeach