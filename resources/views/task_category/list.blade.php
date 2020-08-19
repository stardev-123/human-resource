
	@if($task_categories->count())
		@foreach($task_categories as $task_category)
			<tr>
				<td>{{$task_category->name}}</td>
				<td>{{$task_category->description}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/task-category/{{$task_category->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
					{!!delete_form(['task-category.destroy',$task_category->id],['table-refresh' => 'task-category-table'])!!}
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="3">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif