<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/app.js'); ?> <!-- All meta and title start-->
    <?php echo $__env->make('layout.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- meta and title end-->

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <?php if(auth()->guard()->check()): ?>
    <!-- Current User ID -->
    <meta name="current-user-id" content="<?php echo e(auth()->id()); ?>">
    <?php endif; ?>

    <!-- css start !-->
    <?php echo $__env->make('layout.css', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- css end !-->
</head>

<body>
    <!-- Loader start-->
    <div class="app-wrapper">
        <!-- Loader start-->
        <?php if (isset($component)) { $__componentOriginal1cf7ddb08d3976da931ed0aee29f0761 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1cf7ddb08d3976da931ed0aee29f0761 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.splash-screen','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('splash-screen'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1cf7ddb08d3976da931ed0aee29f0761)): ?>
<?php $attributes = $__attributesOriginal1cf7ddb08d3976da931ed0aee29f0761; ?>
<?php unset($__attributesOriginal1cf7ddb08d3976da931ed0aee29f0761); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1cf7ddb08d3976da931ed0aee29f0761)): ?>
<?php $component = $__componentOriginal1cf7ddb08d3976da931ed0aee29f0761; ?>
<?php unset($__componentOriginal1cf7ddb08d3976da931ed0aee29f0761); ?>
<?php endif; ?>
        <!-- Loader end-->

        <!-- Menu Navigation start -->
        <?php echo $__env->make('layout.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <!-- Menu Navigation end -->

        <!-- Sidebar Overlay per mobile -->
        <div class="sidebar-overlay"></div>

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

        <!-- Chat Widget -->
        <?php if(auth()->guard()->check()): ?>
        <div class="chat-widget position-fixed" style="bottom: 20px; right: 140px; z-index: 1050;">
            <a href="<?php echo e(route('chat.index')); ?>" class="btn btn-primary btn-lg rounded-circle shadow-lg d-flex align-items-center justify-content-center chat-widget-btn"
               data-chat-badge-container
               style="width: 60px; height: 60px; transition: all 0.3s ease;"
               title="<?php echo e(__('chat.title')); ?>">
                <i class="ph-duotone ph-chat f-s-24"></i>
                <?php if(auth()->user()->unreadChatNotifications()->count() > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger chat-notification-badge"
                          id="chat-notification-badge"
                          style="font-size: 0.7rem; animation: pulse 2s infinite;">
                        <?php echo e(auth()->user()->unreadChatNotifications()->count()); ?>

                    </span>
                <?php endif; ?>
            </a>
        </div>

        <style>
        .chat-widget-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(0,123,255,0.3) !important;
        }

        .chat-notification-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

            /* Mobile responsive */
            @media (max-width: 768px) {
                .chat-widget {
                    bottom: 15px !important;
                    right: 135px !important;
                }

                .chat-widget-btn {
                    width: 50px !important;
                    height: 50px !important;
                    border-radius: 50% !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    padding: 0 !important;
                }

                .chat-widget-btn i {
                    font-size: 20px !important;
                    line-height: 1 !important;
                }
            }
        </style>
        <?php endif; ?>

        <!-- Footer Section start -->
        <?php echo $__env->make('layout.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <!-- Footer Section end -->
    </div>

<?php echo app('Tighten\Ziggy\BladeRouteGenerator')->generate(); ?>
    <!-- Search Configuration -->
    <?php echo $__env->make('layout.search-config', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
    // Definisci currentUser globalmente per i badge delle notifiche
  const currentUserIdMeta = document.querySelector('meta[name="current-user-id"]');

  if (currentUserIdMeta) {
    window.currentUser = {
      id: parseInt(currentUserIdMeta.content, 10)
    };
  } else {
    // No current user meta tag found
  }

    // Inizializza il sistema badge globale dopo che è stato definito
  if (window.currentUser?.id) {
    // Inizializza subito per avere il badge visibile
    if (!window.globalBadgeInitialized) {
      if (typeof initGlobalChatBadge === 'function') {
        try {
          initGlobalChatBadge();
          window.globalBadgeInitialized = true;
        } catch (error) {
          // Error initializing global badge system
        }
      } else {
        // initGlobalChatBadge function not found
      }
    }
  } else {
    // No current user, cannot initialize global badge system
  }

  if (!window.Echo) {
    // Echo not initialized, try lazy-init if available
    if (window.initEcho && typeof window.initEcho === 'function') {
      try { window.initEcho(); } catch (_) {}
    }
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
    .subscribed(() => {})
    .listen('.user-presence', (e) => {
      // e: { userId, state, ttl }
      const state = e.state || 'offline';
      applyStateToAll(e.userId, state);
    });

  // 🔹 (Facoltativo) il tuo listener login privato, se ti serve ancora
  if (window.Echo.private) {
    window.Echo.private('user-logins')
      .listen('.user-login', (e) => {
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

            // 🔹 Notifica chat per-utente: mostra toast se non siamo nella pagina chat
        try {
          const currentUserIdMeta = document.querySelector('meta[name="current-user-id"]');
          const currentUserId = currentUserIdMeta ? parseInt(currentUserIdMeta.content, 10) : (window.currentUser && (window.currentUser.id || window.currentUser.user_id));
          if (currentUserId && Number.isFinite(currentUserId)) {

            window.Echo.private(`App.Models.User.${currentUserId}`)
              .subscribed(() => {})
              .error((err) => {})
              // Debug: ascolta tutti gli eventi per vedere cosa arriva
              .listen('*', (e) => {

              })
              .listen('.chat.message.notify', (e) => {
                const inChatPage = !!document.querySelector('[data-chat-room]') || (location.pathname || '').startsWith('/chat');
                const isOwn = String(e.senderId) === String(currentUserId);
                if (inChatPage || isOwn) return;

            const container = document.createElement('div');
            container.className = 'd-flex align-items-center gap-2';

            if (e.avatarUrl) {
              const img = document.createElement('img');
              img.src = e.avatarUrl;
              img.alt = e.senderName || 'avatar';
              img.className = 'rounded-circle flex-shrink-0';
              img.style.width = '32px';
              img.style.height = '32px';
              img.style.objectFit = 'cover';
              container.appendChild(img);
            }

            const textWrap = document.createElement('div');
            const title = document.createElement('div');
            title.innerHTML = `<strong>${(e.senderName || 'Qualcuno')}</strong> ti ha mandato un messaggio`;
            const preview = document.createElement('div');
            preview.className = 'text-white-50 small';
            preview.textContent = e.preview || 'Nuovo messaggio';
            textWrap.appendChild(title);
            textWrap.appendChild(preview);
            container.appendChild(textWrap);

            if (typeof Toastify !== 'undefined') {
              Toastify({
                node: container,
                duration: 6000,
                gravity: 'top',
                position: 'right',
                backgroundColor: 'var(--bs-primary)',
                className: 'shadow-lg',
                stopOnFocus: true,
                close: true,
                onClick: () => { window.location.href = `/chat?room=${encodeURIComponent(e.roomId)}`; },
              }).showToast();
            } else {

            }
          });
      }
    } catch (err) {
      // Chat toast init error
    }
  }
});

// Sistema Badge Globale per Chat
function initGlobalChatBadge() {
  // Controlla se è già stato inizializzato
  if (window.globalBadgeInitialized) {
    return;
  }

  if (!window.currentUser?.id) {
    return;
  }

        // Sistema di storage temporaneo per badge individuali
  if (!window.individualBadgeStorage) {
    window.individualBadgeStorage = new Map();
  } else {
    // Reset dello storage per evitare conteggi errati
    window.individualBadgeStorage.clear();
  }

  // Verifica se ci sono chiamate API che potrebbero popolare lo storage
  if (!window.notificationAPICalls) {
    window.notificationAPICalls = new Set();
  }

    // Cerca il badge generale nella sidebar
  const badgeContainer = document.querySelector('[data-chat-badge-container]');

  const badgeElement = badgeContainer?.querySelector('#chat-notification-badge');

  let currentCount = 0;
  if (badgeElement) {
    currentCount = parseInt(badgeElement.textContent) || 0;
  }

    // Funzione per aggiornare il badge
  function updateBadge(count) {
    currentCount = Math.max(0, count);

    if (badgeElement) {
      if (currentCount > 0) {
        badgeElement.textContent = currentCount;
        badgeElement.style.display = 'inline-block';
      } else {
        badgeElement.style.display = 'none';
      }
    } else if (badgeContainer && currentCount > 0) {
      // Controlla se esiste già un badge
      const existingBadge = badgeContainer.querySelector('#chat-notification-badge');
      if (existingBadge) {
        existingBadge.textContent = currentCount;
        existingBadge.style.display = 'inline-block';
        return;
      }

      // Crea il badge se non esiste
      const newBadge = document.createElement('span');
      newBadge.id = 'chat-notification-badge';
      newBadge.className = 'badge bg-danger badge-notification ms-2';
      newBadge.textContent = currentCount;
      badgeContainer.appendChild(newBadge);
    }

    // Emetti evento per i sistemi locali
    document.dispatchEvent(new CustomEvent('globalBadgeUpdated', {
      detail: { count: currentCount }
    }));
  }

  // Funzione per incrementare il badge
  function incrementBadge() {
    updateBadge(currentCount + 1);
  }

  // Funzione per decrementare il badge
  function decrementBadge() {
    updateBadge(currentCount - 1);
  }

      // Funzione per configurare i listener Echo
  function setupEchoListeners() {
    if (!window.Echo) {
      return false;
    }

                try {
        // Ascolta sullo stesso canale del toast (App.Models.User.{id})
        const channelName = `App.Models.User.${window.currentUser.id}`;

        window.Echo.private(channelName)
          .subscribed(() => {})
          .error((err) => {})
          // Listener per tutti gli eventi per debug
          .listen('*', (e) => {})
                    .listen('.notification.created', (e) => {
            if (e.notification.type === 'chat_message') {
              incrementBadge();

              // Emetti evento per i badge individuali
              document.dispatchEvent(new CustomEvent('globalBadgeUpdated', {
                detail: {
                  type: 'chat_message',
                  data: e.notification.data,
                  action: 'created'
                }
              }));
            }
          })
          // Listener per eventi senza punto (fallback)
          .listen('notification.created', (e) => {})
          // Listener per eventi chat generici
          .listen('.chat.message', (e) => {})
          .listen('chat.message', (e) => {})
                              // Listener per l'evento che funziona nel toast
          .listen('.chat.message.notify', (e) => {
            // Controlla se questo evento è già stato processato (evita duplicati)
            const eventId = `${e.roomId || 'unknown'}-${e.senderId || 'unknown'}-${Date.now()}`;
            if (window.processedEvents && window.processedEvents.has(eventId)) {
              return;
            }

            // Marca evento come processato
            if (!window.processedEvents) window.processedEvents = new Set();
            window.processedEvents.add(eventId);

            // Rimuovi eventi vecchi (più di 5 secondi)
            setTimeout(() => {
              if (window.processedEvents) window.processedEvents.delete(eventId);
            }, 5000);

                        // Aggiorna badge individuale in background PRIMA di incrementare il badge generale
            // Prova diverse possibili chiavi per il roomId
            const roomId = e.roomId || e.room_id || e.chat_room_id || e.data?.room_id || e.data?.chat_room_id;

            if (roomId) {
              // Verifica se il sistema è disponibile
              let newCount = 1; // Default count

              if (window.GlobalChatBadge && window.GlobalChatBadge.getIndividualBadgeCount) {
                // Aggiorna il conteggio in background
                const currentCount = window.GlobalChatBadge.getIndividualBadgeCount(roomId);
                newCount = currentCount + 1;

                window.GlobalChatBadge.updateIndividualBadge(roomId, newCount);
              }

              // Emetti evento per i badge individuali (NON per il badge generale)
              document.dispatchEvent(new CustomEvent('individualBadgeUpdated', {
                detail: { roomId, count: newCount }
              }));
            }

            // Incrementa il badge generale DOPO aver aggiornato quelli individuali
            incrementBadge();
          })
        .listen('.notification.updated', (e) => {
          if (e.notification.type === 'chat_message') {
            updateBadge(e.notification.data?.unread_count || currentCount);
          }
        })
        .listen('.notification.deleted', (e) => {
          if (e.notification.type === 'chat_message') {
            decrementBadge();
          }
        });

      return true;
    } catch (error) {
      return false;
    }
  }

  // Ascolta le notifiche chat in tempo reale
  if (window.Echo) {
    setupEchoListeners();
  } else {
    // Retry più aggressivo quando Echo diventa disponibile
    let retryCount = 0;
    const maxRetries = 20; // 10 secondi totali

    const checkEcho = setInterval(() => {
      retryCount++;

      if (window.Echo) {
        clearInterval(checkEcho);

        if (setupEchoListeners()) {
          // Echo listeners setup successful
        } else {
          // Failed to setup Echo listeners
        }
      } else if (retryCount >= maxRetries) {
        clearInterval(checkEcho);
      }
    }, 500);
  }

  // Gestisce il click sul pulsante chat per nascondere il badge
  if (badgeContainer) {
    badgeContainer.addEventListener('click', () => {
      updateBadge(0);

      // Marka le notifiche chat come lette via API
      markChatNotificationsAsRead();
    });
  }

  // Funzione per markare le notifiche come lette
  async function markChatNotificationsAsRead() {
    try {
      const response = await fetch('/chat/notifications/mark-all-read', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      if (response.ok) {
        // Chat notifications marked as read
      } else {
        // Failed to mark notifications as read
      }
    } catch (error) {
      // Error marking notifications as read
    }
  }

  // Funzioni per gestire badge individuali in background
  function updateIndividualBadgeInBackground(roomId, count) {
    if (!window.individualBadgeStorage) return;

    window.individualBadgeStorage.set(roomId, count);

    // Emetti evento per aggiornare badge individuali se sono visibili
    document.dispatchEvent(new CustomEvent('individualBadgeUpdated', {
      detail: { roomId, count }
    }));
  }

  function getIndividualBadgeCount(roomId) {
    if (!window.individualBadgeStorage) return 0;
    const count = window.individualBadgeStorage.get(roomId) || 0;
    return count;
  }

  function getAllIndividualBadgeCounts() {
    if (!window.individualBadgeStorage) return {};
    const counts = {};
    window.individualBadgeStorage.forEach((count, roomId) => {
      counts[roomId] = count;
    });
    return counts;
  }

    // Esporta le funzioni per uso esterno
  window.GlobalChatBadge = {
    updateBadge,
    incrementBadge,
    decrementBadge,
    getCurrentCount: () => currentCount,
    updateIndividualBadge: updateIndividualBadgeInBackground,
    getIndividualBadgeCount,
    getAllIndividualBadgeCounts
  };

  // Global chat badge system initialized

  // Marca come inizializzato
  window.globalBadgeInitialized = true;

  // Se currentUser è già definito, inizializza subito
  if (window.currentUser?.id) {
    // La funzione è già stata chiamata implicitamente
  }

    // Listener globale per quando Echo diventa disponibile
  document.addEventListener('echoReady', () => {
    if (window.currentUser?.id) {
      // Non reinizializzare tutto, solo setup Echo listeners
      setupEchoListeners();
    }
  });
}


    </script>


</body>



</html>
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/layout/master.blade.php ENDPATH**/ ?>