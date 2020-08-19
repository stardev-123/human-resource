
       	<h2><strong>{!!trans('messages.assigned').'</strong> '.trans('messages.user')!!}

        </h2>
        <div style="padding:5px 10px;">
        	@if($task->User->count())
	        	@foreach($task->user as $user)
	        		<div class="row" style="margin-bottom: 5px;">
	        			<div style="width:60px;float:left;">
	        				{!! getAvatar($user->id,45) !!}
	        			</div>
	        			<p>{{$user->name_with_designation_and_department}}</p>
	        		</div>
	        	@endforeach
        	@else
        		@if($task->user_id == Auth::user()->id)
        			<div class="alert alert-danger"><i class="fa fa-times icon"></i> <a href="#" data-href="/task/{{$task->id}}/edit" data-toggle="modal" data-target="#myModal" style="color: inherit;">{{trans('messages.assign_user_info')}}</a></div>
        		@endif
        	@endif
        </div>
       	<h2><strong>{!!trans('messages.task').'</strong> '.trans('messages.description')!!}</h2>
		<div class="custom-scrollbar" style="margin-top: 15px;">
        	<div class="the-notes info">
				{!! $task->description !!}
			</div>
        </div>

        @if($uploads->count())
            <h2><strong>{!!trans('messages.task').'</strong> '.trans('messages.attachment')!!}</h2>
            @foreach($uploads as $upload)
                <p><i class="fa fa-paperclip"></i> <a href="/task/{{$upload->uuid}}/download">{{$upload->user_filename}}</a></p>
            @endforeach
        @endif

        @if($task->progress == '100')
            <h2><strong>{!!trans('messages.sign_off').'</strong> '.trans('messages.request')!!}</h2>
            @if($task->TaskSignOffRequest->count())
                <div class="table-responsive">
                    <table class="table table-stripped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>{{trans('messages.user')}}</th>
                                <th>{{trans('messages.remarks')}}</th>
                                <th>{{trans('messages.status')}}</th>
                                <th>{{trans('messages.date')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($task->TaskSignOffRequest as $sign_off)
                                <tr>
                                    <td>{{$sign_off->User->full_name}}</td>
                                    <td>{{$sign_off->remarks}}</td>
                                    <td>{{toWord($sign_off->status)}}</td>
                                    <td>{{showDateTime($sign_off->created_at)}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div style="margin:10px 0;"></div>

            @if(($task->sign_off_status == null || $task->sign_off_status == 'rejected' || $task->sign_off_status == 'cancelled') && in_array(Auth::user()->id,$task->User->pluck('id')->all()))
                <div class="form-group">
                        <textarea class="form-control" name="remarks" id="task-sign-off-remarks" data-show-counter="1" data-limit="{{config('config.textarea_limit')}}" data-autoresize="1" placeholder="Message to Reviewer"></textarea>
                </div>
                <div class="form-group">
                    <a href="#" class="btn btn-primary task-sign-off-request" data-task-id="{{$task->id}}" data-url="/task-sign-off-request" data-action="request">{{trans('messages.request').' '.trans('messages.sign_off')}}</a>
                </div>
            @elseif($task->sign_off_status == 'approved')
                <div class="form-group">
                    <button class="btn btn-success disabled">{{trans('messages.sign_off').' '.trans('messages.approved')}}</button>
                    <a href="#" class="btn btn-primary task-sign-off-request" data-task-id="{{$task->id}}" data-url="/task-sign-off-request-action" data-action="cancelled">{{trans('messages.cancel').' '.trans('messages.sign_off')}}</a>
                </div>
                
                <div class="form-group">
                    <textarea class="form-control" name="remarks" id="task-sign-off-remarks" data-show-counter="1" data-limit="{{config('config.textarea_limit')}}" data-autoresize="1" placeholder="Cancel Remarks"></textarea>
                </div>

            @elseif($task->sign_off_status == 'requested' && $task->user_id == Auth::user()->id)
                <div class="form-group">
                        <textarea class="form-control" name="remarks" id="task-sign-off-remarks" data-show-counter="1" data-limit="{{config('config.textarea_limit')}}" data-autoresize="1" placeholder="Message to Requester"></textarea>
                </div>
                <div class="form-group">
                    <a href="#" class="btn btn-success task-sign-off-request" data-task-id="{{$task->id}}" data-action="approved" data-url="/task-sign-off-request-action">{{trans('messages.approve').' '.trans('messages.sign_off')}}</a> 
                    <a href="#" class="btn btn-danger task-sign-off-request" data-task-id="{{$task->id}}" data-action="rejected" data-url="/task-sign-off-request-action">{{trans('messages.reject').' '.trans('messages.sign_off')}}</a>
                </div>
            @endif
        @endif