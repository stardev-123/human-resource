
        @if($clock_status == 'clock_in')
            <a href="#" data-ajax="1" data-source="/clock/in" data-refresh="load-clock-button" data-table-refresh="clock-list-table" class="btn btn-success"> {!! trans('messages.clock_in') !!} </a>
        @elseif($clock_status == 'clock_out')
            <button class="btn btn-success disabled"><i class="fa fa-arrow-circle-right"></i> {!! trans('messages.you_are_clock_in') !!}</button>

            <a href="#" data-ajax="1" data-source="/clock/out" data-refresh="load-clock-button" data-table-refresh="clock-list-table" class="btn btn-danger"> {!! trans('messages.clock_out') !!} </a>
        @endif