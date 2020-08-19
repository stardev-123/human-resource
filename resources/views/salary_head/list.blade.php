
	@if($salary_heads->count())
		@foreach($salary_heads as $salary_head)
			<tr>
				<td>{{$salary_head->name}}</td>
				<td>{{toWord($salary_head->type)}}</td>
				<td>{!! ($salary_head->is_fixed) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}</td>
				<td>{{$salary_head->description}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/salary-head/{{$salary_head->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
					{!!delete_form(['salary-head.destroy',$salary_head->id],['table-refresh' => 'salary-head-table'])!!}
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="5">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif