
    <audio id="buzzer" src="/notification-music/<?php echo e(config('config.default_notification_tone')); ?>" type="audio/ogg"></audio>
    <div id="js-var" style="visibility:none;"
        data-toastr-position="<?php echo e(config('config.notification_position')); ?>"
        data-something-error-message="<?php echo e(trans('messages.something_wrong')); ?>"
        data-character-remaining="<?php echo e(trans('messages.character_remaining')); ?>"
        data-textarea-limit="<?php echo e(config('config.textarea_limit')); ?>"
        data-processing-messsage="<?php echo e(trans('messages.processing_message')); ?>"
        data-redirecting-messsage="<?php echo e(trans('messages.redirecting_message')); ?>"
        data-new-notification="<?php echo e(trans('messages.new_notification')); ?>"
        data-show-error-message="<?php echo e(config('config.show_error_messages')); ?>"
        data-action-confirm-message="<?php echo e(trans('messages.action_confirm_message')); ?>"
        data-menu="<?php echo e(isset($menu) ? $menu : ''); ?>"
        data-calendar-language="<?php echo config('localization.'.session('localization').'.calendar'); ?>"
        data-datepicker-language="<?php echo config('localization.'.session('localization').'.datepicker'); ?>"
        data-datatable-language="/vendor/datatables/locale/<?php echo config('localization.'.session('localization').'.datatable'); ?>.json"
    ></div>

    <?php echo Html::script('vendor/jquery.min.js'); ?>

    <?php echo Html::script('vendor/bootstrap.min.js'); ?>

    <?php echo Html::script('js/sidebar.js'); ?>

    <?php echo Html::script('vendor/jquery-ui/jquery-ui.min.js'); ?>

    <?php echo Html::script('vendor/slimscroll/jquery.slimscroll.min.js'); ?>

    <?php echo Html::script('vendor/sortable/sortable.min.js'); ?>

    <?php echo Html::script('vendor/select/js/bootstrap-select.min.js'); ?>

    <?php echo Html::script('vendor/toastr/toastr.min.js'); ?>

    <?php echo $__env->make('global.toastr_notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo Html::script('vendor/page/page.min.js'); ?>

    <?php if(isset($assets) && in_array('summernote',$assets)): ?>
        <?php echo Html::script('vendor/summernote/summernote.js'); ?>

    <?php endif; ?>
    <?php echo Html::script('vendor/password/password.js'); ?>

    <?php echo Html::script('vendor/input/bootstrap.file-input.js'); ?>

    <?php echo Html::script('vendor/switch/bootstrap-switch.min.js'); ?>

    <?php echo Html::script('vendor/datepicker/js/bootstrap-datepicker.js'); ?>

    <?php if(session('localization') != 'en'): ?>
        <?php echo Html::script('vendor/datepicker/locales/bootstrap-datepicker.'.config('localization.'.session('localization').'.datepicker').'.min.js'); ?>

    <?php endif; ?>
    <?php if(isset($assets) && in_array('datatable',$assets)): ?>
        <?php echo Html::script('vendor/datatables/datatables.min.js'); ?>

    <?php endif; ?>
    <?php if(isset($assets) && in_array('calendar',$assets)): ?>
        <?php echo Html::script('vendor/calendar/moment.min.js'); ?>

        <?php echo Html::script('vendor/calendar/fullcalendar.min.js'); ?>

        <?php echo Html::script('vendor/calendar/locale-all.js'); ?>

    <?php endif; ?>
    <?php if(isset($assets) && in_array('recaptcha',$assets) && config('config.enable_recaptcha')): ?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    <?php endif; ?>
    <?php if(isset($assets) && in_array('tags',$assets)): ?>
        <?php echo Html::script('vendor/tags/tags.min.js'); ?>

    <?php endif; ?>
    <?php if(isset($assets) && in_array('slider',$assets)): ?>
        <?php echo Html::script('vendor/slider/bootstrap-slider.min.js'); ?>

    <?php endif; ?>
    <?php if(isset($assets) && in_array('redactor',$assets)): ?>
        <?php echo Html::script('vendor/redactor/redactor.min.js'); ?>

    <?php endif; ?>
    <?php if(isset($assets) && in_array('timepicker',$assets)): ?>
        <?php echo Html::script('vendor/timepicker/bootstrap-clockpicker.min.js'); ?>

    <?php endif; ?>
    <?php if(isset($assets) && in_array('datetimepicker',$assets)): ?>
        <?php echo Html::script('vendor/datetimepicker/js/bootstrap-datetimepicker.min.js'); ?>

    <?php endif; ?>
    <?php if(isset($assets) && in_array('form-wizard',$assets)): ?>
        <?php echo Html::script('vendor/wizard/jquery.snippet.js'); ?>

        <?php echo Html::script('vendor/wizard/jquery.easyWizard.js'); ?>

    <?php endif; ?>
    <?php echo Html::script('vendor/bootbox.min.js'); ?>

    <?php echo Html::script('vendor/icheck/icheck.min.js'); ?>


    <?php if(isset($assets) && in_array('graph',$assets)): ?>
        <?php echo Html::script('vendor/echarts-all.js'); ?>

    <?php endif; ?>

    <?php echo Html::script('js/upload.js'); ?>


    <?php if(config('config.enable_push_notification')): ?>
    <script src="https://js.pusher.com/3.1/pusher.min.js"></script>
    <?php endif; ?>

    <script>
      var available_date = <?php echo isset($available_date) ? json_encode($available_date) : json_encode([]); ?>;
      var default_datetimepicker_date = <?php echo isset($default_datetimepicker_date) ? json_encode($default_datetimepicker_date) : json_encode(date('Y-m-d')); ?>;
    </script>
    <?php echo Html::script('js/wmlab.js'); ?>

    <?php echo $__env->make('global.misc', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </body>
</html>
