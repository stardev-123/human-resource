    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell" data-toggle="tooltip" data-title="{{trans('messages.notification')}}" data-placement="bottom"  ></i>
        @if($notifications->count())
        <span class="label label-danger absolute notification-count">{{$notifications->count()}}</span>
        @endif
    </a>
    @if($notifications->count())
    <ul class="dropdown-menu dropdown-message animated half flipInX">
        <li class="dropdown-header notif-header">{{trans('messages.new').' '.trans('messages.notification')}}</li>
        <li class="divider"></li>
        @foreach($top_notification as $notification)
        <li class="unread">
            <a href="{{url($notification->url)}}">
            <div style="float:left;margin:0px 15px 0 0px;">{!! getAvatar($notification->user_id,40) !!}</div>
            <p><strong>{{$notification->User->full_name}}</strong><br />
            {{$notification->description}} <br />
            <span><i>{{timeAgo($notification->created_at)}}</i></span></p>
            </a>
        </li>
        @endforeach
        @if($notifications->count())
        <li class="dropdown-footer"><a href="/notification"><i class="fa fa-arrow-circle-right"></i> {{trans('messages.see_all').' '.trans('messages.notification')}}</a></li>
        @endif
    </ul>
    @else
        <ul class="dropdown-menu dropdown-message animated half flipInX">
            <li><a href="/notification"><i class="fa fa-arrow-circle-right"></i> {{trans('messages.no').' '.trans('messages.new').' '.trans('messages.notification')}}</a></li>
        </ul>
    @endif
    