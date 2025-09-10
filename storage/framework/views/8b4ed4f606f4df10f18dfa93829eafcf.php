<?php
use App\Helpers\SplashScreenHelper;
$randomSlogan = SplashScreenHelper::getRandomSlogan();
$allSlogans = SplashScreenHelper::getAllSlogans();
?>

<div class="loader-wrapper">
    <div class="loader_24" data-splash-text="<?php echo e($randomSlogan); ?>">
        <!-- The text will be dynamically set by CSS and JavaScript -->
    </div>
</div>

<script>
// Immediate execution - no waiting for DOM
(function() {
    const slogans = <?php echo json_encode($allSlogans, 15, 512) ?>;
    const initialSlogan = <?php echo json_encode($randomSlogan, 15, 512) ?>;

    console.log('Splash slogans loaded:', slogans);
    console.log('Initial slogan:', initialSlogan);

    // Set initial slogan immediately
    const loader = document.querySelector('.loader_24');
    if (loader) {
        console.log('Loader found, setting initial slogan');
        loader.setAttribute('data-splash-text', initialSlogan);

        // Change slogan every 2 seconds
        let sloganIndex = 0;
        const intervalId = setInterval(function() {
            if (slogans.length > 0) {
                sloganIndex = (sloganIndex + 1) % slogans.length;
                const newSlogan = slogans[sloganIndex];
                console.log('Changing to slogan:', newSlogan);
                loader.setAttribute('data-splash-text', newSlogan);
            }
        }, 2000);

        // Clean up when loader is removed
        const checkForRemoval = setInterval(function() {
            if (!document.querySelector('.loader_24')) {
                clearInterval(intervalId);
                clearInterval(checkForRemoval);
                console.log('Loader removed, cleanup complete');
            }
        }, 100);
    } else {
        console.log('Loader not found');
    }
})();
</script>
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/components/splash-screen.blade.php ENDPATH**/ ?>