
	@if($qualification_languages->count())
		@foreach($qualification_languages as $qualification_language)
			<tr>
				<td>{{$qualification_language->name}}</td>
				<td>{{$qualification_language->description}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/qualification-language/{{$qualification_language->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
					{!!delete_form(['qualification-language.destroy',$qualification_language->id],['table-refresh' => 'qualification-language-table'])!!}
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="3">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif