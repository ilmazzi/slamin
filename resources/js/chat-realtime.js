// Chat realtime: invio + ricezione (costruisce il markup client-side)
// Requisiti: window.Echo inizializzato (resources/js/bootstrap.js)

(function () {
    function initMessaging() {
      const chatContainer = document.querySelector('[data-chat-room]');
      if (!chatContainer) return;

      const roomId = chatContainer.getAttribute('data-chat-room');
      if (!roomId) return;

      const messagesBox = document.querySelector('[data-chat-messages]');
      if (!messagesBox) return;

      const form  = document.querySelector('[data-chat-form]');
      const input = form ? form.querySelector('[data-chat-input]') : null;

      // --- Rilevamento current user id ---
      function toIntOrZero(v) {
        const n = parseInt(String(v ?? '0'), 10);
        return Number.isFinite(n) && n > 0 ? n : 0;
      }

      const me =
        toIntOrZero(document.querySelector('meta[name="current-user-id"]')?.getAttribute('content')) ||
        toIntOrZero(chatContainer.getAttribute('data-current-user-id')) ||
        toIntOrZero(window.currentUser && (window.currentUser.id || window.currentUser.user_id));

      // --- Helpers escape ---
      function escapeHtml(s) {
        return String(s)
          .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
          .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
      }
      const escapeAttr = escapeHtml;

      // --- Costruzione markup messaggio ---
      function buildMessageHtml({ content, time, sender_id, avatar_url }) {
        const isOwn = Number(sender_id) === me;

        if (isOwn) {
          // PROPRI: a destra
          return `
  <div class="d-flex position-relative mb-3">
    <div class="chat-box-right ms-auto text-end">
      <div>
        <p class="chat-text mb-1">${escapeHtml(content)}</p>
        <p class="text-muted mb-0"><i class="ti ti-checks text-primary"></i> ${escapeHtml(time)}</p>
      </div>
    </div>
    <div class="chatdp h-45 w-45 b-r-50 position-absolute end-0 top-0 bg-danger">
      <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto" data-user-id="${sender_id}">
        <img alt="avatar" class="img-fluid b-r-10" src="${escapeAttr(avatar_url)}">
        <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary" data-presence-dot></span>
      </span>
    </div>
  </div>`;
        }

        // ALTRUI: a sinistra
        return `
  <div class="d-flex position-relative mb-3">
    <div class="chatdp h-45 w-45 b-r-50 position-absolute start-0 bg-light">
      <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto" data-user-id="${sender_id}">
        <img alt="avatar" class="img-fluid b-r-10" src="${escapeAttr(avatar_url)}">
        <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary" data-presence-dot></span>
      </span>
    </div>
    <div class="chat-box">
      <div>
        <p class="chat-text mb-1">${escapeHtml(content)}</p>
        <p class="text-muted mb-0"><i class="ti ti-checks text-primary"></i> ${escapeHtml(time)}</p>
      </div>
    </div>
  </div>`;
      }

      function appendMessageHtml(html) {
        let atBottom = false;
        try {
          atBottom = messagesBox.scrollHeight - messagesBox.scrollTop - messagesBox.clientHeight < 80;
        } catch (_) {}

        const wrap = document.createElement('div');
        wrap.innerHTML = html.trim();
        Array.from(wrap.children).forEach((n) => messagesBox.appendChild(n));

        if (atBottom) {
          try { messagesBox.scrollTop = messagesBox.scrollHeight; } catch (_) {}
        }
      }

      // --- ricezione ---
      if (window.Echo) {
        window.Echo.private(`chat.room.${roomId}`)
          .listen('.chat.message', (e) => {
            const html = buildMessageHtml(e);
            appendMessageHtml(html);
          });
      }

      // --- invio ---
      if (form && input) {
        form.addEventListener('submit', async (ev) => {
          ev.preventDefault();
          const content = (input.value || '').trim();
          if (!content) return;

          const submitBtn = form.querySelector('[type="submit"]');
          if (submitBtn) submitBtn.disabled = true;

          try {
            const resp = await fetch(form.action, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              },
              body: JSON.stringify({ content }),
            });
            if (!resp.ok) throw new Error(`Send failed: ${resp.status}`);
            input.value = ''; // apparirà via evento
          } catch (err) {
            console.error('[chat-realtime] send error', err);
          } finally {
            if (submitBtn) submitBtn.disabled = false;
          }
        }, { once: false });
      }

      try { messagesBox.scrollTop = messagesBox.scrollHeight; } catch (_) {}
    }

    // --- Nuova Chat: ricerca utenti + creazione privata ---
    function initNewChat() {
      const input = document.getElementById('userSearch');
      const results = document.getElementById('searchResults');
      const spinner = document.getElementById('loadingSpinner');
      const emptyBox = document.getElementById('noResults');

      if (!input || !results) return;

      let debounceId = null;
      let lastQuery = '';

      function setLoading(isLoading) {
        if (spinner) spinner.classList.toggle('d-none', !isLoading);
      }

      function clearResults(showEmpty = false) {
        results.innerHTML = '';
        if (emptyBox) emptyBox.classList.toggle('d-none', !showEmpty);
      }

      async function search(q) {
        try {
          setLoading(true);
          const resp = await fetch(`/chat/search-users?q=${encodeURIComponent(q)}`, {
            headers: { 'Accept': 'application/json' }
          });
          if (!resp.ok) throw new Error(`search ${resp.status}`);
          const data = await resp.json();
          const users = Array.isArray(data.users) ? data.users : [];

          if (users.length === 0) {
            clearResults(true);
            return;
          }

          if (emptyBox) emptyBox.classList.add('d-none');

          const html = users.map(u => `
            <div class="d-flex align-items-center py-2 border-bottom">
              <div class="me-2">${u.avatar_html || ''}</div>
              <div class="flex-grow-1">
                <div class="f-w-500">${(u.name || '').replace(/</g,'&lt;')}</div>
              </div>
              <div>
                <button class="btn btn-sm btn-primary" data-start-chat data-user-id="${u.id}">
                  <i class="ti ti-brand-hipchat"></i> Avvia
                </button>
              </div>
            </div>`).join('');

          results.innerHTML = html;
        } catch (err) {
          console.error('[chat-new] search error', err);
          clearResults(true);
        } finally {
          setLoading(false);
        }
      }

      async function createChat(userId, button) {
        try {
          if (button) button.disabled = true;
          const resp = await fetch(`/chat/create-private/${encodeURIComponent(userId)}`, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Accept': 'application/json',
            },
          });
          if (!resp.ok) throw new Error(`create ${resp.status}`);
          const data = await resp.json();
          if (data && data.success && data.chat_id) {
            const base = (data.redirect && String(data.redirect)) || '/chat';
            window.location.href = `${base}?room=${encodeURIComponent(data.chat_id)}`;
          }
        } catch (err) {
          console.error('[chat-new] create error', err);
        } finally {
          if (button) button.disabled = false;
        }
      }

      input.addEventListener('input', () => {
        const q = (input.value || '').trim();
        if (q.length < 2) {
          clearResults(false);
          return;
        }
        if (q === lastQuery) return;
        lastQuery = q;
        if (debounceId) clearTimeout(debounceId);
        debounceId = setTimeout(() => search(q), 300);
      }, { passive: true });

      results.addEventListener('click', (ev) => {
        const btn = ev.target.closest('[data-start-chat]');
        if (!btn) return;
        const userId = btn.getAttribute('data-user-id');
        if (!userId) return;
        createChat(userId, btn);
      });
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => { initMessaging(); initNewChat(); }, { once: true });
    } else {
      initMessaging();
      initNewChat();
    }
  })();
