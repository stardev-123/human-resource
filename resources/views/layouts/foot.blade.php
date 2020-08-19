
    <audio id="buzzer" src="/notification-music/{{config('config.default_notification_tone')}}" type="audio/ogg"></audio>
    <div id="js-var" style="visibility:none;"
        data-toastr-position="{{config('config.notification_position')}}"
        data-something-error-message="{{trans('messages.something_wrong')}}"
        data-character-remaining="{{trans('messages.character_remaining')}}"
        data-textarea-limit="{{config('config.textarea_limit')}}"
        data-processing-messsage="{{trans('messages.processing_message')}}"
        data-redirecting-messsage="{{trans('messages.redirecting_message')}}"
        data-new-notification="{{trans('messages.new_notification')}}"
        data-show-error-message="{{config('config.show_error_messages')}}"
        data-action-confirm-message="{{trans('messages.action_confirm_message')}}"
        data-menu="{{isset($menu) ? $menu : ''}}"
        data-calendar-language="{!! config('localization.'.session('localization').'.calendar') !!}"
        data-datepicker-language="{!! config('localization.'.session('localization').'.datepicker') !!}"
        data-datatable-language="/vendor/datatables/locale/{!! config('localization.'.session('localization').'.datatable') !!}.json"
    ></div>

    {!! Html::script('vendor/jquery.min.js') !!}
    {!! Html::script('vendor/bootstrap.min.js') !!}
    {!! Html::script('js/sidebar.js') !!}
    {!! Html::script('vendor/jquery-ui/jquery-ui.min.js') !!}
    {!! Html::script('vendor/slimscroll/jquery.slimscroll.min.js') !!}
    {!! Html::script('vendor/sortable/sortable.min.js') !!}
    {!! Html::script('vendor/select/js/bootstrap-select.min.js') !!}
    {!! Html::script('vendor/toastr/toastr.min.js') !!}
    @include('global.toastr_notification')
    {!! Html::script('vendor/page/page.min.js') !!}
    @if(isset($assets) && in_array('summernote',$assets))
        {!! Html::script('vendor/summernote/summernote.js') !!}
    @endif
    {!! Html::script('vendor/password/password.js') !!}
    {!! Html::script('vendor/input/bootstrap.file-input.js') !!}
    {!! Html::script('vendor/switch/bootstrap-switch.min.js') !!}
    {!! Html::script('vendor/datepicker/js/bootstrap-datepicker.js') !!}
    @if(session('localization') != 'en')
        {!! Html::script('vendor/datepicker/locales/bootstrap-datepicker.'.config('localization.'.session('localization').'.datepicker').'.min.js') !!}
    @endif
    @if(isset($assets) && in_array('datatable',$assets))
        {!! Html::script('vendor/datatables/datatables.min.js') !!}
    @endif
    @if(isset($assets) && in_array('calendar',$assets))
        {!! Html::script('vendor/calendar/moment.min.js') !!}
        {!! Html::script('vendor/calendar/fullcalendar.min.js') !!}
        {!! Html::script('vendor/calendar/locale-all.js') !!}
    @endif
    @if(isset($assets) && in_array('recaptcha',$assets) && config('config.enable_recaptcha'))
        <script src='https://www.google.com/recaptcha/api.js'></script>
    @endif
    @if(isset($assets) && in_array('tags',$assets))
        {!! Html::script('vendor/tags/tags.min.js') !!}
    @endif
    @if(isset($assets) && in_array('slider',$assets))
        {!! Html::script('vendor/slider/bootstrap-slider.min.js') !!}
    @endif
    @if(isset($assets) && in_array('redactor',$assets))
        {!! Html::script('vendor/redactor/redactor.min.js') !!}
    @endif
    @if(isset($assets) && in_array('timepicker',$assets))
        {!! Html::script('vendor/timepicker/bootstrap-clockpicker.min.js') !!}
    @endif
    @if(isset($assets) && in_array('datetimepicker',$assets))
        {!! Html::script('vendor/datetimepicker/js/bootstrap-datetimepicker.min.js') !!}
    @endif
    @if(isset($assets) && in_array('form-wizard',$assets))
        {!! Html::script('vendor/wizard/jquery.snippet.js') !!}
        {!! Html::script('vendor/wizard/jquery.easyWizard.js') !!}
    @endif
    {!! Html::script('vendor/bootbox.min.js') !!}
    {!! Html::script('vendor/icheck/icheck.min.js') !!}

    @if(isset($assets) && in_array('graph',$assets))
        {!! Html::script('vendor/echarts-all.js') !!}
    @endif

    {!! Html::script('js/upload.js') !!}

    @if(config('config.enable_push_notification'))
    <script src="https://js.pusher.com/3.1/pusher.min.js"></script>
    @endif

    <script>
      var available_date = {!! isset($available_date) ? json_encode($available_date) : json_encode([]) !!};
      var default_datetimepicker_date = {!! isset($default_datetimepicker_date) ? json_encode($default_datetimepicker_date) : json_encode(date('Y-m-d')) !!};
    </script>
    {!! Html::script('js/wmlab.js') !!}
    @include('global.misc')
    </body>
</html>
