
	@if($education_levels->count())
		@foreach($education_levels as $education_level)
			<tr>
				<td>{{$education_level->name}}</td>
				<td>{{$education_level->description}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/education-level/{{$education_level->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
					{!!delete_form(['education-level.destroy',$education_level->id],['table-refresh' => 'education-level-table'])!!}
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="3">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif