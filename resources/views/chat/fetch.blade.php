<ul class="media-list">
@foreach($chats as $chat)
    <li class="media">
    <a class="pull-{{($chat->user_id == Auth::user()->id) ? 'right' : 'left'}}" href="#">
      {!! getAvatar($chat->user_id,45) !!}
    </a>
    <div class="media-body {{getColor()}}">
      <strong>{{$chat->User->full_name}}</strong><br />
      {{$chat->message}}
      <p class="time">{{timeAgo($chat->created_at)}}</p>
    </div>
@endforeach
</ul>