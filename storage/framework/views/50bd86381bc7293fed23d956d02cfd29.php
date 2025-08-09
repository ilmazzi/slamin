<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/app.js'); ?> <!-- All meta and title start-->
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
    <script>
 // Aggiorna stato utente (dot + label) quando arriva l'evento broadcast.
// Requisito markup:
//  - wrapper con data-user-id="<id>"
//  - dot:   [data-presence-dot]
//  - label: [data-presence-label] (con stesso data-user-id)

document.addEventListener('DOMContentLoaded', () => {
  if (!window.Echo) {
    console.warn('[presence] Echo non inizializzato');
    return;
  }

  const LABELS = {
    online:  'Online',
    recent:  'Attivo di recente',
    idle:    'Assente',
    offline: 'Offline',
  };

  const LABEL_CLASSES = {
    online:  'text-success',
    recent:  'text-info',
    idle:    'text-warning',
    offline: 'text-secondary',
  };

  function applyStateToAll(userId, state) {
    // 1) DOT
    document.querySelectorAll(`[data-user-id="${userId}"] [data-presence-dot]`).forEach((dot) => {
      dot.classList.remove('bg-success', 'bg-info', 'bg-warning', 'bg-secondary');
      switch (state) {
        case 'online':  dot.classList.add('bg-success');  break;
        case 'recent':  dot.classList.add('bg-info');     break;
        case 'idle':    dot.classList.add('bg-warning');  break;
        default:        dot.classList.add('bg-secondary'); // offline/default
      }
      dot.setAttribute('title', LABELS[state] ?? LABELS.offline);
    });

    // 2) LABEL TESTO (+ colore)
    document.querySelectorAll(`[data-presence-label][data-user-id="${userId}"]`).forEach((el) => {
      // testo
      el.textContent = LABELS[state] ?? LABELS.offline;

      // colore (opzionale)
      el.classList.remove('text-success', 'text-info', 'text-warning', 'text-secondary');
      el.classList.add(LABEL_CLASSES[state] ?? LABEL_CLASSES.offline);
    });
  }

  // 🔹 Stato online (canale pubblico rinominato: presence.online)
  window.Echo
    .channel('user-presence')
    .subscribed(() => console.log('[presence] Subscribed user-presence'))
    .listen('.user-presence', (e) => {
      // e: { userId, state, ttl }
      const state = e.state || 'offline';
      console.log('[presence] user-presence', e);
      applyStateToAll(e.userId, state);
    });

  // 🔹 (Facoltativo) il tuo listener login privato, se ti serve ancora
  if (window.Echo.private) {
    window.Echo.private('user-logins')
      .listen('.user-login', (e) => {
        console.log('[LOGIN]', e);
        if (typeof Toastify !== 'undefined') {
          Toastify({
            text: `🔐 Utente loggato: ${e.user.name} (${e.user.email})`,
            duration: 5000,
            gravity: 'top',
            position: 'right',
            backgroundColor: '#28a745',
            stopOnFocus: true,
            close: true,
          }).showToast();
        }
      });
  }
});


    </script>


</body>



</html>
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/layout/master.blade.php ENDPATH**/ ?>