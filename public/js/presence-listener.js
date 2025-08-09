// Assumi che window.Echo sia già inizializzato (es. in bootstrap.js)
(function () {
  if (!window.Echo) return;

  // Mappa userId -> timer
  const offlineTimers = new Map();

  // TTL di default se non arriva dal server (fallback)
  const DEFAULT_TTL = parseInt(document.documentElement.dataset.onlineTtl || '120', 10);

  // Utility per settare il pallino
  function setDot(userId, state) {
    // Aggiorna dot nei container con data-user-id
    const roots = document.querySelectorAll(`[data-user-id="${userId}"]`);
    const updateDot = (dot) => {
      dot.classList.remove('bg-success', 'bg-info', 'bg-warning', 'bg-secondary');
      if (state === 'online') dot.classList.add('bg-success');
      else if (state === 'recent') dot.classList.add('bg-info');
      else if (state === 'idle') dot.classList.add('bg-warning');
      else dot.classList.add('bg-secondary');
    };

    roots.forEach((root) => {
      const dot = root.querySelector('[data-presence-dot]');
      if (dot) updateDot(dot);
    });

    // Aggiorna eventuali dot globali con data-presence-dot e data-user-id (ridondanza utile)
    const globalDots = document.querySelectorAll(`[data-presence-dot][data-user-id="${userId}"]`);
    globalDots.forEach(updateDot);

    // Etichette testuali
    const labels = document.querySelectorAll(`[data-presence-label][data-user-id="${userId}"]`);
    labels.forEach((el) => {
      el.textContent = state.charAt(0).toUpperCase() + state.slice(1);
      el.classList.remove('text-success', 'text-secondary', 'text-info', 'text-warning');
      if (state === 'online') el.classList.add('text-success');
      else if (state === 'recent') el.classList.add('text-info');
      else if (state === 'idle') el.classList.add('text-warning');
      else el.classList.add('text-secondary');
    });
  }

  function startOfflineTimer(userId, ttl) {
    clearOfflineTimer(userId);
    const ms = Math.max(1, ttl) * 1000;
    const timerId = setTimeout(() => {
      setDot(userId, 'offline');
      offlineTimers.delete(userId);
    }, ms);
    offlineTimers.set(userId, timerId);
  }

  function clearOfflineTimer(userId) {
    if (!offlineTimers.has(userId)) return;
    clearTimeout(offlineTimers.get(userId));
    offlineTimers.delete(userId);
  }

  // Ascolta gli heartbeat sul canale broadcast
  window.Echo.channel('presence.online')
    .listen('.presence.updated', (e) => {
      const userId = e.userId;
      const state  = e.state || 'online';
      const ttl    = typeof e.ttl === 'number' ? e.ttl : DEFAULT_TTL;

      setDot(userId, state);
      startOfflineTimer(userId, ttl);
    });

})();
