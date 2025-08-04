<!DOCTYPE html>
<html lang="en">

<head>
    <!-- All meta and title start-->
<?php echo $__env->make('layout.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!-- meta and title end-->

<!-- CSRF Token -->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<!-- css start !-->
<?php echo $__env->make('layout.css', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!-- css end !-->
</head>

<body>
<!-- Loader start-->
<div class="app-wrapper">
    <!-- Loader start-->
    <div class="loader-wrapper">
        <div class="loader_24"></div>
    </div>
    <!-- Loader end-->

    <!-- Menu Navigation start -->
<?php echo $__env->make('layout.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!-- Menu Navigation end -->


    <div class="app-content">
        <!-- Header Section start -->
    <?php echo $__env->make('layout.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Header Section end -->

        <!-- Main Section start -->
        <main>
            
            <?php echo $__env->yieldContent('main-content'); ?>
        </main>
        <!-- Main Section end -->
    </div>

    <!-- tap on top -->
    <div class="go-top">
      <span class="progress-value">
        <i class="ti ti-arrow-up"></i>
      </span>
    </div>

    <!-- Footer Section start -->
     <?php echo $__env->make('layout.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Footer Section end -->
</div>

<!--customizer-->
<div id="customizer"></div>

<!-- scripts start-->
<?php echo $__env->make('layout.script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!-- scripts end-->
</body>



</html>
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/layout/master.blade.php ENDPATH**/ ?>