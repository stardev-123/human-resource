
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! $library->title !!}</h4>
	</div>
	<div class="modal-body">
		{!! $library->description !!}
        @if($uploads->count())
            <hr />
            @foreach($uploads as $upload)
                <p><i class="fa fa-paperclip"></i> <a href="/library/{{$upload->uuid}}/download">{{$upload->user_filename}}</a></p>
            @endforeach
        @endif
        <hr />
		<span class="timeinfo"><i class="fa fa-clock-o"></i> {!! showDateTime($library->created_at) !!}</span>
		<span class="pull-right">
		{!! $library->UserAdded->name_with_designation_and_department !!}
		</span>
		<div class="clear"></div>
	</div>