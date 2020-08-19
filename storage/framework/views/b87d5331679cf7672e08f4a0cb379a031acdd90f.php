<?php echo $__env->make('layouts.head', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <body class="tooltips full-content">
    <div class="container">
        <img id="loading-img" src="/images/loading.gif" />
        <?php echo $__env->yieldContent('content'); ?>

        <?php echo $__env->make('global.credit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>

    <div class="overlay"></div>
    <div class="modal fade-scale" id="myModal" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            </div>
        </div>
    </div>

<?php echo $__env->make('layouts.foot', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>