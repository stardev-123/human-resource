
        <div class="left side-menu">
            <div class="body rows scroll-y">
                <div class="sidebar-inner slimscroller">
                    <div class="media">
                        <a class="pull-left" href="#">
                            {!!getAvatar(Auth::user()->id,60)!!}
                        </a>
                        <div class="media-body">
                            {{trans('messages.welcome')}},
                            <h4 class="media-heading"><strong>{{Auth::user()->full_name}}</strong></h4>
                            <small>{{trans('messages.last_login').' '.Auth::user()->last_login}}
                            @if(Auth::user()->last_login_ip)
                            | {{trans('messages.from').' '.Auth::user()->last_login_ip}}
                            @endif
                            </small>
                        </div>
                    </div>
                    <div id="sidebar-menu">
                        <ul id="sidebar-menu-list">
                        </ul>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
