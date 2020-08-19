@if (Session::has('success'))
    <script>
        toastr.success("{!! Session::get('success') !!}",'',{"positionClass": "{{config('config.notification_position')}}"})
    </script>
@endif

@if (Session::has('errors'))
    <script>
        toastr.error("{!! Session::get('errors')->first() !!}",'',{"positionClass": "{{config('config.notification_position')}}"})
    </script>
@endif

@if (Session::has('status'))
    <script>
        toastr.info("{!! Session::get('status') !!}",'',{"positionClass": "{{config('config.notification_position')}}"})
    </script>
@endif
