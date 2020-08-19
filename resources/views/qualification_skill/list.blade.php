
	@if($qualification_skills->count())
		@foreach($qualification_skills as $qualification_skill)
			<tr>
				<td>{{$qualification_skill->name}}</td>
				<td>{{$qualification_skill->description}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/qualification-skill/{{$qualification_skill->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
					{!!delete_form(['qualification-skill.destroy',$qualification_skill->id],['table-refresh' => 'qualification-skill-table'])!!}
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="3">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif