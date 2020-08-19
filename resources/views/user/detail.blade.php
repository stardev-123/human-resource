    
    <div class="col-sm-12">
        <div class="box-info">
            <h2><strong>{!!trans('messages.user').'</strong> '.trans('messages.profile')!!}</h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-8  col-md-offset-2">{!! getAvatar($user->id,150) !!}</div>
                        <div class="col-md-12">
                            @if(Auth::user()->id == $user->id)
                                <div class="row" style="padding-top:10px;">
                                    <div class="col-md-7">
                                        <a href="#" data-href="/change-password" data-toggle="modal" data-target="#myModal" class="btn btn-block btn-primary btn-sm">{{trans('messages.change').' '.trans('messages.password')}}</a>
                                    </div>
                                    <div class="col-md-5">
                                        <a href="#" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();" class="btn btn-block btn-danger btn-sm">{{trans('messages.logout')}}</a>
                                    </div>
                                </div>
                            @endif
                            <br />
                            <p style="font-weight: bold;text-align: center;">{{trans('messages.profile').' '.trans('messages.complete')}} <a href="#" data-href="/user/{{$user->id}}/profile-setup" data-toggle="modal" data-target="#myModal"><i class="fa fa-question-circle" data-toggle="tooltip" title="{{toWordTranslate('profile-complete-percentage')}}" style="color: #000000;"></i> </a></p>
                            <div class="progress">
                                <div class="progress-bar progress-bar-{{progressColor($setup_percentage)}}" role="progressbar" aria-valuenow="{{$setup_percentage}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$setup_percentage}}%;">
                                {{$setup_percentage}}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="table-responsive">
                        <table class="table table-stripped table-hover show-table">
                            <thead>
                                <tr>
                                    <th>{{trans('messages.name')}}</th>
                                    <td>
                                        {{$user->full_name}}
                                        @if($user->Profile->gender)
                                            ({{trans('messages.'.$user->Profile->gender)}})
                                        @endif
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>{{trans('messages.user').' '.trans('messages.code')}}</th>
                                    <td>{{$user->Profile->employee_code}}</td>
                                </tr>
                                <tr>
                                    <th>{{trans('messages.role')}}</th>
                                    <td>
                                        @foreach($user->roles as $role)
                                            {{ucfirst($role->name)}}<br />
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{trans('messages.designation')}}</th>
                                    <td>{{$user->designation_name}}</td>
                                </tr>
                                <tr>
                                    <th>{{trans('messages.department')}}</th>
                                    <td>{{$user->department_name}}</td>
                                </tr>
                                <tr>
                                    <th>{{trans('messages.location')}}</th>
                                    <td>{{$user->location_name}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="table-responsive">
                        <table class="table table-stripped table-hover show-table">
                            <thead>
                                <tr>
                                    <th>{{trans('messages.status')}}</th>
                                    <td>{{toWord($user->status)}}</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>{{trans('messages.email')}}</th>
                                    <td>{{$user->email}}</td>
                                </tr>
                                @if(!config('config.login'))
                                <tr>
                                    <th>{{trans('messages.username')}}</th>
                                    <td>{{$user->username}}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>{{trans('messages.date_of').' '.trans('messages.joining')}}</th>
                                    <td>
                                        @if($user_employment)
                                            {{showDate($user_employment->date_of_joining)}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{trans('messages.date_of').' '.trans('messages.birth')}}</th>
                                    <td>
                                        @if($user->Profile->date_of_birth)
                                            <strong>({{ getAge($user->Profile->date_of_birth) }} Yr)</strong>
                                        @endif
                                        {{showDate($user->Profile->date_of_birth)}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{trans('messages.anniversary')}}</th>
                                    <td>
                                        @if($user->Profile->date_of_anniversary)
                                            <strong>({{ getAge($user->Profile->date_of_anniversary) }} Yr)</strong>
                                        @endif
                                        {{showDate($user->Profile->date_of_anniversary)}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>