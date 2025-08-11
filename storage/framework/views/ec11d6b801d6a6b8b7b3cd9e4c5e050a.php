<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="IE=edge" http-equiv="X-UA-Compatible">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta content="Multipurpose, super flexible, powerful, clean modern responsive bootstrap 5 admin template"
      name="description">
<meta content="admin template, ki-admin admin template, dashboard template, flat admin template, responsive admin template, web app"
      name="keywords">
<meta content="la-themes" name="author">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php if(auth()->guard()->check()): ?>
<meta name="current-user-id" content="<?php echo e(auth()->id()); ?>">
<?php endif; ?>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

<link rel="icon" href="<?php echo e(('../assets/images/Loghino_biancosunero.png')); ?>" type="image/x-icon">
<link rel="shortcut icon" href="<?php echo e(('../assets/images/Loghino_biancosunero.png')); ?>" type="image/x-icon">

<title><?php echo $__env->yieldContent('title'); ?> | Slam In - A home for poetry</title>


<?php /**PATH C:\xampp\htdocs\slamin\resources\views/layout/head.blade.php ENDPATH**/ ?>
