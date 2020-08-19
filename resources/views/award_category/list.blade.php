
	@if($award_categories->count())
		@foreach($award_categories as $award_category)
			<tr>
				<td>{{$award_category->name}}</td>
				<td>{{$award_category->description}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/award-category/{{$award_category->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
					{!!delete_form(['award-category.destroy',$award_category->id],['table-refresh' => 'award-category-table'])!!}
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="3">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif