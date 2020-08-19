
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! $daily_report->User->name_with_designation_and_department !!}</h4>
	</div>
	<div class="modal-body">
		{{trans('messages.date').' : '.showDate($daily_report->date)}}
        <hr />
		{!! $daily_report->description !!}
        @if($uploads->count())
            <hr />
            @foreach($uploads as $upload)
                <p><i class="fa fa-paperclip"></i> <a href="/daily-report/{{$upload->uuid}}/download">{{$upload->user_filename}}</a></p>
            @endforeach
        @endif
        <hr />
		<span class="timeinfo"><i class="fa fa-clock-o"></i> {!! trans('messages.created_at').' '.showDateTime($daily_report->created_at) !!}</span>
		<span class="pull-right">
		{!! trans('messages.updated_at').' '.showDateTime($daily_report->updated_at) !!}
		</span>
		<div class="clear"></div>
	</div>