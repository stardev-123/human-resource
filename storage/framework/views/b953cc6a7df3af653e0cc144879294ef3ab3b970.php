<?php if(Session::has('success')): ?>
    <script>
        toastr.success("<?php echo Session::get('success'); ?>",'',{"positionClass": "<?php echo e(config('config.notification_position')); ?>"})
    </script>
<?php endif; ?>

<?php if(Session::has('errors')): ?>
    <script>
        toastr.error("<?php echo Session::get('errors')->first(); ?>",'',{"positionClass": "<?php echo e(config('config.notification_position')); ?>"})
    </script>
<?php endif; ?>

<?php if(Session::has('status')): ?>
    <script>
        toastr.info("<?php echo Session::get('status'); ?>",'',{"positionClass": "<?php echo e(config('config.notification_position')); ?>"})
    </script>
<?php endif; ?>
