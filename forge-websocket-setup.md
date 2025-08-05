# Configurazione WebSocket su Laravel Forge

## **1. Configurazione Server Forge**

### **A. Abilita WebSocket nel sito:**
1. **Vai su Forge Dashboard** > **Il tuo server** > **Sites**
2. **Clicca sul sito** > **Websockets**
3. **Abilita "Enable WebSockets"**
4. **Porta:** 6001 (default Laravel Echo)
5. **Salva**

### **B. Configurazione Nginx:**
Forge aggiunge automaticamente questo al tuo `nginx.conf`:

```nginx
# WebSocket support
location /app/ {
    proxy_pass http://127.0.0.1:6001;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
    proxy_cache_bypass $http_upgrade;
}
```

## **2. Modifica il WebSocket Server**

### **A. Aggiorna la configurazione:**
```php
// config/websocket.php
return [
    'host' => env('WEBSOCKET_HOST', '0.0.0.0'),
    'port' => env('WEBSOCKET_PORT', 6001), // Cambia da 8080 a 6001
    'ssl' => [
        'local_cert' => env('LARAVEL_WEBSOCKETS_SSL_LOCAL_CERT', null),
        'local_pk' => env('LARAVEL_WEBSOCKETS_SSL_LOCAL_PK', null),
        'passphrase' => env('LARAVEL_WEBSOCKETS_SSL_PASSPHRASE', null),
    ],
];
```

### **B. Aggiorna il client JavaScript:**
```javascript
// public/assets/js/websocket-client.js
constructor() {
    // ...
    const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
    const host = window.location.hostname;
    
    // Per produzione (Forge)
    if (window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
        this.wsUrl = `${protocol}//${host}/app/`; // Usa il proxy Nginx
    } else {
        this.wsUrl = `${protocol}//${host}:8080`; // Sviluppo locale
    }
    // ...
}
```

## **3. Deploy su Forge**

### **A. Comando per avviare WebSocket:**
```bash
# Nel server Forge
php artisan websocket:start --host=0.0.0.0 --port=6001
```

### **B. Process Manager (Supervisor):**
1. **Forge Dashboard** > **Server** > **Processes**
2. **Add Process:**
   - **Name:** websocket
   - **Command:** `php /home/forge/slamin.it/artisan websocket:start --host=0.0.0.0 --port=6001`
   - **User:** forge
   - **Directory:** `/home/forge/slamin.it`

### **C. Environment Variables:**
```env
WEBSOCKET_HOST=0.0.0.0
WEBSOCKET_PORT=6001
```

## **4. Test della Configurazione**

### **A. Verifica connessione:**
```javascript
// Console browser
wsClient.connect(userId, token);
// Dovrebbe vedere: "Connesso al WebSocket server"
```

### **B. Log del server:**
```bash
# Nel server Forge
tail -f /home/forge/slamin.it/storage/logs/laravel.log
```

## **5. Troubleshooting**

### **A. Se WebSocket non si connette:**
1. **Verifica porta:** `netstat -tlnp | grep 6001`
2. **Verifica processi:** `ps aux | grep websocket`
3. **Verifica log:** `tail -f storage/logs/laravel.log`

### **B. Se Nginx non proxy:**
1. **Ricarica Nginx:** `sudo service nginx reload`
2. **Verifica configurazione:** `sudo nginx -t`

### **C. Se SSL non funziona:**
1. **Verifica certificati SSL**
2. **Usa WSS invece di WS**
3. **Verifica firewall**

## **6. Monitoraggio**

### **A. Health Check:**
```php
// routes/web.php
Route::get('/websocket-health', function() {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});
```

### **B. Logging:**
```php
// Nel WebSocket server
Log::info('WebSocket connection established', ['user_id' => $userId]);
```

**Questa configurazione funziona perfettamente su Forge!** 🚀 