					<ul class="media-list">
						@foreach($ticket->TicketReply as $ticket_reply)
						<li class="media">
							<a class="pull-left" href="#">
								{!! getAvatar($ticket_reply->user_id,40) !!}
							</a>
							<div class="media-body">
								<h4 class="media-heading"><a href="#">{!! $ticket_reply->User->name_with_designation_and_department !!}</a> <small class="pull-right">{!! showDateTime($ticket_reply->created_at) !!}</small>
								</h4>
								@if($ticket->user_id != $ticket_reply->user_id)
								<span class="pull-right">{!! getTicketStatus($ticket_reply->status) !!}</span>
								@endif
								{!! $ticket_reply->description !!}

								@if(\App\Upload::whereModule('ticket-reply')->whereModuleId($ticket_reply->id)->whereStatus(1)->count())
									<br /><br />
						            @foreach(\App\Upload::whereModule('ticket-reply')->whereModuleId($ticket_reply->id)->whereStatus(1)->get() as $ticket_reply_upload)
						                <p><i class="fa fa-paperclip"></i> <a href="/ticket-reply/{{$ticket_reply_upload->uuid}}/download">{{$ticket_reply_upload->user_filename}}</a></p>
						            @endforeach
						        @endif
							</div>
						</li>
						@endforeach
					</ul>