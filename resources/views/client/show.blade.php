<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
	<h4 class="modal-title">{!! trans('messages.view').' '.trans('messages.client') !!}</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-3">
			<strong>{{ trans('messages.first').' '.trans('messages.name') }}:</strong>
			{!! $client->first_name !!}
		</div>
		<div class="col-md-9">
			<strong>{{ trans('messages.last').' '.trans('messages.name') }}:</strong>
			{!! $client->last_name !!}
		</div>
	</div></br>
	<div class="row">
		<div class="col-md-4">
			<strong>{{ trans('messages.email') }}:</strong>
			{!! $client->email !!}
		</div>
		<div class="col-md-4">
			<strong>{{ trans('messages.gender') }}:</strong>
			{!! $client->gender !!}
		</div>
		<div class="col-md-4">
			<strong>{{ trans('messages.date_of').' '.trans('messages.birth') }}:</strong>
			{!! $client->date_of_birth !!}
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<strong>{{ trans('messages.phone') }}:</strong>
			{!! $client->phone !!}
		</div>
		<div class="col-md-6">
			<strong>{{ trans('messages.address') }}:</strong>
			{!! '<br>'.$client->address_line_1.'<br>'.$client->address_line_2.'<br>'.$client->city.'<br>'.$client->state.'<br>'.$client->zipcode !!}
		</div>
	</div>
	<div class="row">
		<div class="col-md-7">
			<strong>{{ trans('messages.note') }}:</strong>
			{!! $client->note !!}
		</div>
		<div class="col-md-5">
			@if($uploads->count())
				<hr />
				@foreach($uploads  as $upload)
				<p><i class="fa fa-paperclip file-uploader"></i> <a href="/client/{{$upload->uuid}}/download">{{$upload->user_filename}}</a></p>
				@endforeach
			@endif
		</div>
	</div>

		<hr />
		</span>
		<div class="clear"></div>
</div>
