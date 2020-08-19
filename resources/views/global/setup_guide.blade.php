
    <div class="progress">
        <div class="progress-bar progress-bar-{{progressColor($setup_percentage)}}" role="progressbar" aria-valuenow="{{$setup_percentage}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$setup_percentage}}%;">
        {{$setup_percentage}}%
        </div>
    </div>
    @foreach($setup->chunk(4) as $chunk)
        <div class="row" style="padding:5px;">
            @foreach($chunk as $setup_guide)
                <div class="col-xs-3">
                    @if($setup_guide->completed)
                        <i class="fa fa-check-circle success fa-2x" style="vertical-align:middle;"></i> {{toWord($setup_guide->module)}}
                    @else
                        <i class="fa fa-times-circle danger fa-2x" style="vertical-align:middle;"></i><a href="/{{config('setup.'.$setup_guide->module.'.link')}}" {{($con && config('setup.'.$setup_guide->module.'.tab') ? 'data-toggle="tab"' : '')}}> {{toWord($setup_guide->module)}}</a>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
    @if($setup_percentage == 100)
        <p class="alert alert-success">Great! You have setup the application completely and ready to use. </p>
    @endif