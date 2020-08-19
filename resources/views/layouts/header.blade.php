
            <div class="header content rows-content-header">

                <button class="button-menu-mobile show-sidebar">
                    <i class="fa fa-bars"></i>
                </button>

                <div class="navbar navbar-default" role="navigation">
                    <div class="container">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <i class="fa fa-angle-double-down"></i>
                            </button>
                        </div>

                        @if($right_sidebar)
                            <a href="#" class="navbar-toggle toggle-right btn btn-sm" data-toggle="sidebar" data-target=".sidebar-right" style="margin-left:10px;">
                              <i class="fa fa-question-circle icon" data-toggle="tooltip" data-title="Help" data-placement="bottom" style="color:#000000;"></i>
                            </a>
                        @endif

                        <div class="navbar-collapse collapse">

                            <ul class="nav navbar-nav">
                                <li>
                                    @if(session('parent_login'))
                                        <a href="#" data-ajax="1" data-source="/login-return"><span class="label label-danger">{{trans('messages.login_back_as',['attribute' => \App\User::whereId(session('parent_login'))->first()->full_name])}}</span> </a>
                                    @endif
                                </li>
                            </ul>

                            <ul class="nav navbar-nav navbar-right top-navbar">

                                @if(!getMode())
                                    <li><a href="/demo-real-time-notification" style="font-weight: bold; color:red;">Test Real Time Notification</a></li>
                                @endif

                                @if(config('config.enable_push_notification'))
                                    <li class="dropdown" id="load-notification-message" data-source="/load-notification">
                                </li>
                                @endif

                                @if(Entrust::can('manage-todo') && config('config.enable_to_do'))
                                <li><a href="#" data-href="/todo" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-list-ul fa-lg icon" data-toggle="tooltip" title="{!! trans('messages.to_do') !!}" data-placement="bottom"></i></a></li>
                                @endif

                                <!--@if(config('config.multilingual') && Entrust::can('change-localization'))
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-language fa-lg icon" data-toggle="tooltip" title="{!! trans('messages.localization') !!}" data-placement="bottom"></i> </a>
                                    <ul class="dropdown-menu animated half flipInX">
                                        <li class="active"><a href="#" style="color:white;cursor:default;">{!! config('localization.'.session('localization').'.localization').' ('.session('localization').')' !!}</a></li>
                                        @foreach(config('localization') as $key => $localization)
                                            @if(session('localization') != $key)
                                            <li><a href="/change-localization/{{$key}}">{!! $localization['localization']." (".$key.")" !!}</a></li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                              @endif-->

                                <li class="dropdown list-container" id="configuration-list">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-cog" data-toggle="tooltip" title="{{trans('messages.configuration')}}" data-placement="bottom"></i>
                                    </a>
                                    <ul class="dropdown-menu animated half flipInX list-data custom-scrollbar">
                                    @if(Entrust::can('manage-configuration'))
                                        <li><a href="/configuration">{{trans('messages.configuration')}}</a></li>
                                    @endif
                                    @if(Entrust::can('manage-role'))
                                        <li><a href="/role">{{trans('messages.role').' '.trans('messages.configuration')}}</a></li>
                                    @endif
                                    @if(Entrust::can('manage-permission'))
                                        <li><a href="/permission">{{trans('messages.permission')}}</a></li>
                                    @endif
                                    @if(Entrust::can('list-department'))
                                        <li><a href="/department">{{trans('messages.department')}}</a></li>
                                    @endif
                                    @if(Entrust::can('list-designation'))
                                        <li><a href="/designation">{{trans('messages.designation')}}</a></li>
                                    @endif
                                    @if(Entrust::can('list-location'))
                                        <li><a href="/location">{{trans('messages.location')}}</a></li>
                                    @endif
                                    @if(Entrust::can('manage-shift'))
                                        <li><a href="/shift">{{trans('messages.shift')}}</a></li>
                                    @endif
                                    @if(config('config.multilingual') && Entrust::can('manage-localization'))
                                        <li><a href="/localization">{{trans('messages.localization')}}</a></li>
                                    @endif
                                    @if(config('config.enable_custom_field') && Entrust::can('manage-custom-field'))
                                        <li><a href="/custom-field">{{trans('messages.custom').' '.trans('messages.field')}}</a></li>
                                    @endif
                                    @if(config('config.enable_ip_filter') && Entrust::can('manage-ip-filter'))
                                        <li><a href="/ip-filter">Ip {{trans('messages.filter')}}</a></li>
                                    @endif
                                    @if(config('config.enable_email_template') && Entrust::can('manage-template'))
                                        <li><a href="/template">{{trans('messages.email').' '.trans('messages.template')}}</a></li>
                                    @endif
                                    @if(Entrust::can('manage-configuration'))
                                        <li><a href="/activity-log">{{trans('messages.activity').' '.trans('messages.log')}}</a></li>
                                    @endif
                                    @if(Entrust::can('manage-email-log'))
                                        <li><a href="/email">{{trans('messages.email').' '.trans('messages.log')}}</a></li>
                                    @endif
                                    @if(Entrust::can('manage-configuration'))
                                        <li><a href="/upload-log">{{trans('messages.upload').' '.trans('messages.log')}}</a></li>
                                        <li><a href="/backup">{{trans('messages.database').' '.trans('messages.backup')}}</a></li>
                                    @endif
                                    </ul>
                                </li>

                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{trans('messages.greeting')}}, <strong>{{Auth::user()->full_name}}</strong> <i class="fa fa-chevron-down i-xs"></i></a>
                                    <ul class="dropdown-menu animated half flipInX">
                                        <li><a href="/profile" ><i class="fa fa-user fa-fw"></i> {!! trans('messages.profile') !!}</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#" data-href="/change-password" data-toggle="modal" data-target="#myModal"><i class="fa fa-key fa-fw"></i> {!! trans('messages.change').' '.trans('messages.password') !!}</a></li>
                                        <li><a href="#" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"><i class="fa fa-sign-out fa-fw"></i> {{trans('messages.logout')}}</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <form id="logout-form" action="/logout" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
