
	@if($document_types->count())
		@foreach($document_types as $document_type)
			<tr>
				<td>{{$document_type->name}}</td>
				<td>{{$document_type->description}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/document-type/{{$document_type->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
					{!!delete_form(['document-type.destroy',$document_type->id],['table-refresh' => 'document-type-table'])!!}
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="3">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif