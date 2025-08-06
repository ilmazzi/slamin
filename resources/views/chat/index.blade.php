@extends('layout.master')

@section('title', 'Chat - Poetry Slam')

@section('main-content')
<div class="row position-relative chat-container-box">
    <!-- Sidebar Chat -->
    <div class="col-lg-4 col-xxl-3 box-col-5">
        <div class="chat-div">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <span class="chatdp h-45 w-45 d-flex-center b-r-50 position-relative bg-primary">
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="" class="img-fluid b-r-50">
                            <span class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                        </span>
                        <div class="flex-grow-1 ps-2">
                            <div class="fs-6">{{ auth()->user()->name }}</div>
                            <div class="text-muted f-s-12">{{ auth()->user()->getRoleDisplayNameAttribute() }}</div>
                        </div>
                        <div>
                            <div class="btn-group dropdown-icon-none">
                                <a role="button" data-bs-placement="top" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                    <i class="ti ti-settings fs-5"></i>
                                </a>
                                <ul class="dropdown-menu" data-popper-placement="bottom-start">
                                    <li><a class="dropdown-item" href="#" onclick="showNewChatModal()"><i class="ti ti-brand-hipchat"></i> <span class="f-s-13">Nuova Chat</span></a></li>
                                    <li><a class="dropdown-item" href="#" onclick="refreshChats()"><i class="ti ti-refresh"></i> <span class="f-s-13">Aggiorna</span></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="close-togglebtn">
                            <a class="ms-2 close-toggle" role="button"><i class="ti ti-align-justified fs-5"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chat-tab-wrapper">
                        <ul class="tabs chat-tabs">
                            <li class="tab-link active" data-tab="1"><i class="ph-fill ph-chat-circle-text f-s-18 me-2"></i>Chat</li>
                            <li class="tab-link" data-tab="2"><i class="ph-fill ph-users f-s-18 me-2"></i>Utenti</li>
                        </ul>
                    </div>
                    <div class="content-wrapper">
                        <!-- Tab 1: Chat List -->
                        <div id="tab-1" class="tabs-content active">
                            <div class="tab-wrapper">
                                <div class="mt-3">
                                    <ul class="nav nav-tabs app-tabs-primary tab-light-primary chat-status-tab border-0 justify-content-between mb-0 pb-0" id="Basic" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="private-tab" data-bs-toggle="tab" data-bs-target="#private-tab-pane" type="button" role="tab" aria-controls="private-tab-pane" aria-selected="false" tabindex="-1">
                                                <i class="ph-fill ph-lock-key-open me-2"></i>Private
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="groups-tab" data-bs-toggle="tab" data-bs-target="#groups-tab-pane" type="button" role="tab" aria-controls="groups-tab-pane" aria-selected="false" tabindex="-1">
                                                <i class="ph-fill ph-users-three me-2"></i>Gruppi
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="BasicContent">
                                        <!-- Private Chat -->
                                        <div class="tab-pane fade show active" id="private-tab-pane" role="tabpanel" aria-labelledby="private-tab" tabindex="0">
                                            <div class="chat-contact" id="private-chat-list">
                                                <!-- Chat list will be loaded here -->
                                                <div class="text-center py-4">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Caricamento...</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Group Chat -->
                                        <div class="tab-pane fade" id="groups-tab-pane" role="tabpanel" aria-labelledby="groups-tab" tabindex="0">
                                            <div class="chat-contact chat-group-list" id="group-chat-list">
                                                <!-- Group chat list will be loaded here -->
                                                <div class="text-center py-4">
                                                    <p class="text-muted">Nessun gruppo disponibile</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="float-end">
                                            <div class="btn-group dropup dropdown-icon-none">
                                                <button class="btn btn-primary icon-btn b-r-22 dropdown-toggle active" type="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                                    <i class="ti ti-plus"></i>
                                                </button>
                                                <ul class="dropdown-menu" data-popper-placement="bottom-start">
                                                    <li><a class="dropdown-item" href="#" onclick="showNewChatModal()"><i class="ti ti-brand-hipchat"></i> <span class="f-s-13">Nuova Chat</span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 2: User Search -->
                        <div id="tab-2" class="tabs-content">
                            <div class="chat-contact tabcontent">
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="user-search-input" placeholder="Cerca utenti..." onkeyup="searchUsers(this.value)">
                                </div>
                                <div id="user-search-results">
                                    <!-- User search results will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="col-lg-8 col-xxl-9 box-col-7">
        <div class="card chat-container-content-box">
            <div class="card-header">
                <div class="chat-header d-flex align-items-center" id="chat-header">
                    <div class="d-lg-none">
                        <a class="me-3 toggle-btn" role="button"><i class="ti ti-align-justified"></i></a>
                    </div>
                    <div class="flex-grow-1 ps-2 pe-2">
                        <div class="fs-6">Seleziona una chat</div>
                        <div class="text-muted f-s-12">Inizia una conversazione</div>
                    </div>
                </div>
            </div>
            <div class="card-body chat-body">
                <div class="chat-container" id="chat-messages">
                    <div class="text-center py-5">
                        <i class="ph ph-chat-circle-text fs-1 text-muted"></i>
                        <p class="text-muted mt-3">Seleziona una chat per iniziare a messaggiare</p>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="chat-footer d-flex">
                    <div class="app-form flex-grow-1">
                        <div class="input-group">
                            <span class="input-group-text bg-secondary ms-2 me-2 b-r-10">
                                <a class="emoji-btn d-flex-center" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Emoji" role="button">
                                    <i class="ti ti-mood-smile f-s-18"></i>
                                </a>
                            </span>
                            <input type="text" class="form-control b-r-6" id="message-input" placeholder="Scrivi un messaggio..." aria-label="Messaggio" onkeypress="handleMessageKeyPress(event)">
                            <button class="btn btn-sm btn-primary ms-2 me-2 b-r-4" type="button" onclick="sendMessage()">
                                <i class="ti ti-send"></i> <span>Invia</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Chat Modal -->
<div class="modal fade" id="newChatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuova Chat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="modal-user-search" placeholder="Cerca utenti..." onkeyup="searchUsersForModal(this.value)">
                </div>
                <div id="modal-user-results">
                    <!-- User results for modal -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Typing Indicator -->
<div id="typing-indicator" class="position-absolute bottom-0 start-0 p-3" style="display: none;">
    <div class="chat-box">
        <div>
            <p class="chat-text text-muted"><i class="ti ti-loader ti-spin"></i> Sta scrivendo...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
<script>
// Global variables
let currentChatId = null;
let currentChat = null;
let typingTimeout = null;

// Initialize Echo
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '88ckbtsgozngp21iupn3',
    wsHost: 'slamin.local',
    wsPort: 80,
    forceTLS: false,
    enabledTransports: ['ws'],
    disableStats: true,
    encrypted: false,
    useTLS: false,
    authEndpoint: '/broadcasting/auth',
    cluster: 'mt1'
});

// Debug Pusher events
if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
    window.Echo.connector.pusher.connection.bind('connected', () => {
        console.log('✅ Pusher connesso');
    });

    window.Echo.connector.pusher.connection.bind('disconnected', () => {
        console.log('❌ Pusher disconnesso');
    });

    window.Echo.connector.pusher.connection.bind('error', (error) => {
        console.log('❌ Errore Pusher:', error);
    });

    window.Echo.connector.pusher.connection.bind('connecting', () => {
        console.log('🔄 Pusher in connessione...');
    });
}

// Initialize chat when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Chat inizializzata');
    console.log('Echo configurato:', window.Echo);
    loadChats();
    setupEventListeners();
    markUserOnline();
});

// Setup event listeners
function setupEventListeners() {
    // Tab switching
    document.querySelectorAll('.tab-link').forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            switchTab(tabId);
        });
    });

    // Message input typing events
    const messageInput = document.getElementById('message-input');
    let typingTimer;

    messageInput.addEventListener('input', function() {
        if (currentChatId) {
            // Send typing started event
            sendTypingEvent(true);

            // Clear previous timer
            clearTimeout(typingTimer);

            // Set timer to stop typing after 2 seconds
            typingTimer = setTimeout(() => {
                sendTypingEvent(false);
            }, 2000);
        }
    });

    // Listen for new messages
    console.log('Configurando listener per messaggi real-time...');
    console.log('Echo object:', window.Echo);

    // Listen for new messages in all user's chats

    // Debug connection events - wait for connection to be ready
    function checkEchoConnection() {
        console.log('Checking Echo connection...');
        console.log('window.Echo:', window.Echo);

        if (window.Echo) {
            console.log('window.Echo.connector:', window.Echo.connector);
            if (window.Echo.connector) {
                console.log('window.Echo.connector.pusher:', window.Echo.connector.pusher);

                if (window.Echo.connector.pusher) {
                    console.log('Pusher connection state:', window.Echo.connector.pusher.connection.state);
                    console.log('Pusher connection options:', window.Echo.connector.pusher.connection.options);

                    // Use Pusher connection directly
                    if (window.Echo.connector.pusher.connection.state === 'connected') {
                        console.log('✅ Echo connesso al server Reverb (via Pusher)');
                        return true;
                    }
                }
            }
        }

        console.log('⏳ Echo non ancora inizializzato, riprovo...');
        return false;
    }

    // Try to check connection every 500ms until successful
    let connectionCheckInterval = setInterval(() => {
        if (checkEchoConnection()) {
            clearInterval(connectionCheckInterval);
        }
    }, 500);

    // Stop trying after 10 seconds
    setTimeout(() => {
        clearInterval(connectionCheckInterval);
        if (!window.Echo || !window.Echo.connector || !window.Echo.connector.connection) {
            console.log('❌ Echo non inizializzato dopo 10 secondi');
        }
    }, 10000);

    // Listen for typing events on user's private channel
    window.Echo.private('App.Models.User.' + {{ auth()->id() }}).listen('user-typing', (e) => {
        if (currentChatId && e.chat_id == currentChatId) {
            showTypingIndicator(e.user_name, e.is_typing);
        }
    });

    // Listen for user status changes
    window.Echo.channel('user-status').listen('user-status-changed', (e) => {
        console.log('Stato utente cambiato:', e);
        // Update online status in chat list
        updateUserStatus(e.user_id, e.status);
    });
}

// Listen for new messages on test channel
function setupChatListener(chatId) {
    if (chatId) {
        console.log('🎧 Configurando listener per test-channel');
        window.Echo.channel('test-channel').listen('new-message', (e) => {
            console.log('🎉 Nuovo messaggio ricevuto:', e);
            if (e.chat_id == chatId) {
                addMessageToChat(e);
                loadChats();
            }
        });
        
        // Also listen for test events
        window.Echo.channel('test-channel').listen('test-event', (e) => {
            console.log('🧪 Test event ricevuto:', e);
            alert('Test event ricevuto: ' + e.message);
        });
    }
}

// Load chats
function loadChats() {
    fetch('/chat/chats')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayChats(data.chats);
            } else {
                console.error('Errore nel caricamento chat:', data.message);
            }
        })
        .catch(error => {
            console.error('Errore nella richiesta:', error);
        });
}

// Display chats in the list
function displayChats(chats) {
    const chatList = document.getElementById('private-chat-list');

    if (chats.length === 0) {
        chatList.innerHTML = '<div class="text-center py-4"><p class="text-muted">Nessuna chat disponibile</p></div>';
        return;
    }

    chatList.innerHTML = chats.map(chat => `
        <div class="chat-contactbox" onclick="selectChat(${chat.id})" style="cursor: pointer;">
            <div class="position-absolute">
                <span class="h-45 w-45 d-flex-center b-r-50 position-relative bg-primary">
                    <img src="${chat.avatar}" alt="" class="img-fluid b-r-50">
                    <span class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                </span>
            </div>
            <div class="flex-grow-1 text-start mg-s-50">
                <p class="mb-0 f-w-500 text-dark txt-ellipsis-1">${chat.name}</p>
                <p class="text-secondary mb-0 f-s-12 mb-0 chat-message">
                    ${chat.last_message ? `<i class="ti ti-checks"></i> ${chat.last_message.message}` : 'Nessun messaggio'}
                </p>
            </div>
            <div>
                <p class="f-s-12 chat-time">${formatTime(chat.updated_at)}</p>
                ${chat.unread_count > 0 ? `<span class="badge bg-danger rounded-pill">${chat.unread_count}</span>` : ''}
            </div>
        </div>
    `).join('');
}

// Select a chat
function selectChat(chatId) {
    currentChatId = chatId;
    loadMessages(chatId);
    setupChatListener(chatId);
    updateChatHeader(chatId);
}

// Load messages for a chat
function loadMessages(chatId) {
    fetch(`/chat/${chatId}/messages`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayMessages(data.messages);
            } else {
                console.error('Errore nel caricamento messaggi:', data.message);
            }
        })
        .catch(error => {
            console.error('Errore nella richiesta:', error);
        });
}

// Display messages
function displayMessages(messages) {
    const chatContainer = document.getElementById('chat-messages');

    if (messages.length === 0) {
        chatContainer.innerHTML = '<div class="text-center py-5"><p class="text-muted">Nessun messaggio</p></div>';
        return;
    }

    chatContainer.innerHTML = messages.map(message => `
        <div class="position-relative">
            ${message.is_from_me ? `
                <div class="chat-box-right">
                    <div>
                        <p class="chat-text">${message.message}</p>
                        <p class="text-muted"><i class="ti ti-checks text-primary"></i> ${formatTime(message.created_at)}</p>
                    </div>
                </div>
                <span class="chatdp h-45 w-45 b-r-50 position-absolute top-0 end-0 bg-primary">
                    <img src="${message.user.avatar}" alt="" class="img-fluid b-r-50">
                </span>
            ` : `
                <div class="chatdp h-45 w-45 b-r-50 position-absolute start-0 bg-light">
                    <img src="${message.user.avatar}" alt="" class="img-fluid b-r-50">
                </div>
                <div class="chat-box">
                    <div>
                        <p class="chat-text">${message.message}</p>
                        <p class="text-muted"><i class="ti ti-checks text-primary"></i> ${formatTime(message.created_at)}</p>
                    </div>
                </div>
            `}
        </div>
    `).join('');

    // Scroll to bottom
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

// Send message
function sendMessage() {
    const input = document.getElementById('message-input');
    const message = input.value.trim();

    if (!message || !currentChatId) return;

    // Clear input
    input.value = '';

    // Send to server
    fetch(`/chat/${currentChatId}/messages`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            addMessageToChat(data.message);
        } else {
            console.error('Errore nell\'invio:', data.message);
        }
    })
    .catch(error => {
        console.error('Errore nella richiesta:', error);
    });
}

// Add message to chat (for real-time updates)
function addMessageToChat(message) {
    const chatContainer = document.getElementById('chat-messages');

    // Handle both API response format and real-time event format
    const isFromMe = message.is_from_me || false;
    const messageText = message.message;
    const createdAt = message.created_at;
    const avatar = message.user?.avatar || message.user_avatar;

    const messageHtml = `
        <div class="position-relative">
            ${isFromMe ? `
                <div class="chat-box-right">
                    <div>
                        <p class="chat-text">${messageText}</p>
                        <p class="text-muted"><i class="ti ti-checks text-primary"></i> ${formatTime(createdAt)}</p>
                    </div>
                </div>
                <span class="chatdp h-45 w-45 b-r-50 position-absolute top-0 end-0 bg-primary">
                    <img src="${avatar}" alt="" class="img-fluid b-r-50">
                </span>
            ` : `
                <div class="chatdp h-45 w-45 b-r-50 position-absolute start-0 bg-light">
                    <img src="${avatar}" alt="" class="img-fluid b-r-50">
                </div>
                <div class="chat-box">
                    <div>
                        <p class="chat-text">${messageText}</p>
                        <p class="text-muted"><i class="ti ti-checks text-primary"></i> ${formatTime(createdAt)}</p>
                    </div>
                </div>
            `}
        </div>
    `;

    chatContainer.insertAdjacentHTML('beforeend', messageHtml);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

// Subscribe to chat channel
function subscribeToChat(chatId) {
    // Note: We don't need to manually subscribe to chat channels
    // All messages come through the user's private channel
    console.log('Chat selezionata:', chatId);
}

// Update chat header
function updateChatHeader(chatId) {
    // Find chat in the list and update header
    const chat = document.querySelector(`[onclick="selectChat(${chatId})"]`);
    if (chat) {
        const name = chat.querySelector('.f-w-500').textContent;
        const header = document.getElementById('chat-header');
        header.innerHTML = `
            <div class="d-lg-none">
                <a class="me-3 toggle-btn" role="button"><i class="ti ti-align-justified"></i></a>
            </div>
            <div class="flex-grow-1 ps-2 pe-2">
                <div class="fs-6">${name}</div>
                <div class="text-muted f-s-12 text-success">Online</div>
            </div>
        `;
    }
}

// Search users
function searchUsers(query) {
    if (query.length < 2) {
        document.getElementById('user-search-results').innerHTML = '';
        return;
    }

    fetch(`/chat/users/search?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayUserSearchResults(data.users);
            }
        })
        .catch(error => {
            console.error('Errore nella ricerca:', error);
        });
}

// Display user search results
function displayUserSearchResults(users) {
    const resultsContainer = document.getElementById('user-search-results');

    if (users.length === 0) {
        resultsContainer.innerHTML = '<p class="text-muted text-center">Nessun utente trovato</p>';
        return;
    }

    resultsContainer.innerHTML = users.map(user => `
        <div class="chat-contactbox" onclick="createChatWithUser(${user.id})" style="cursor: pointer;">
            <div class="position-absolute">
                <span class="h-45 w-45 d-flex-center b-r-50 position-relative bg-primary">
                    <img src="${user.avatar}" alt="" class="img-fluid b-r-50">
                </span>
            </div>
            <div class="flex-grow-1 text-start mg-s-50">
                <p class="mb-0 f-w-500 text-dark">${user.name}</p>
                <p class="text-secondary mb-0 f-s-12">${user.nickname || ''}</p>
            </div>
        </div>
    `).join('');
}

// Create chat with user
function createChatWithUser(userId) {
    fetch('/chat/private/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal if open
            const modal = bootstrap.Modal.getInstance(document.getElementById('newChatModal'));
            if (modal) modal.hide();

            // Select the new chat
            selectChat(data.chat_id);
            loadChats(); // Refresh chat list
        } else {
            console.error('Errore nella creazione chat:', data.message);
        }
    })
    .catch(error => {
        console.error('Errore nella richiesta:', error);
    });
}

// Show new chat modal
function showNewChatModal() {
    const modal = new bootstrap.Modal(document.getElementById('newChatModal'));
    modal.show();
}

// Search users for modal
function searchUsersForModal(query) {
    if (query.length < 2) {
        document.getElementById('modal-user-results').innerHTML = '';
        return;
    }

    fetch(`/chat/users/search?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayModalUserResults(data.users);
            }
        })
        .catch(error => {
            console.error('Errore nella ricerca:', error);
        });
}

// Display user results for modal
function displayModalUserResults(users) {
    const resultsContainer = document.getElementById('modal-user-results');

    if (users.length === 0) {
        resultsContainer.innerHTML = '<p class="text-muted text-center">Nessun utente trovato</p>';
        return;
    }

    resultsContainer.innerHTML = users.map(user => `
        <div class="d-flex align-items-center py-2 border-bottom" onclick="createChatWithUser(${user.id})" style="cursor: pointer;">
            <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-primary me-3">
                <img src="${user.avatar}" alt="" class="img-fluid b-r-50">
            </span>
            <div class="flex-grow-1">
                <p class="mb-0 f-w-500">${user.name}</p>
                <p class="text-muted mb-0 f-s-12">${user.nickname || ''}</p>
            </div>
        </div>
    `).join('');
}

// Handle message input key press
function handleMessageKeyPress(event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
}

// Show typing indicator
function showTypingIndicator(userName, isTyping) {
    const indicator = document.getElementById('typing-indicator');
    if (isTyping) {
        indicator.innerHTML = `
            <div class="chat-box">
                <div>
                    <p class="chat-text text-muted"><i class="ti ti-loader ti-spin"></i> ${userName} sta scrivendo...</p>
                </div>
            </div>
        `;
        indicator.style.display = 'block';
    } else {
        indicator.style.display = 'none';
    }
}

// Send typing event
function sendTypingEvent(isTyping) {
    if (!currentChatId) return;

    fetch(`/chat/${currentChatId}/typing`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ is_typing: isTyping })
    }).catch(error => {
        console.error('Errore nell\'invio evento digitazione:', error);
    });
}

// Mark user as online
function markUserOnline() {
    fetch('/online-status/update-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: 'online' })
    });
}

// Format time
function formatTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;

    if (diff < 60000) { // Less than 1 minute
        return 'Ora';
    } else if (diff < 3600000) { // Less than 1 hour
        return `${Math.floor(diff / 60000)}m fa`;
    } else if (diff < 86400000) { // Less than 1 day
        return date.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
    } else {
        return date.toLocaleDateString('it-IT');
    }
}

// Switch tabs
function switchTab(tabId) {
    // Remove active class from all tabs and contents
    document.querySelectorAll('.tab-link').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tabs-content').forEach(content => content.classList.remove('active'));

    // Add active class to selected tab and content
    document.querySelector(`[data-tab="${tabId}"]`).classList.add('active');
    document.getElementById(`tab-${tabId}`).classList.add('active');
}

// Refresh chats
function refreshChats() {
    loadChats();
}

// Update user status
function updateUserStatus(userId, status) {
    // Update status indicators in chat list
    const statusIndicators = document.querySelectorAll(`[data-user-id="${userId}"] .status-indicator`);
    statusIndicators.forEach(indicator => {
        indicator.className = `status-indicator ${status === 'online' ? 'bg-success' : 'bg-secondary'}`;
    });
}
</script>
@endpush
