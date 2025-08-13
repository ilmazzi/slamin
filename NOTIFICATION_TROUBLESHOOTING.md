# 🔧 Troubleshooting Notifiche in Produzione

## 🚨 Problema
Le notifiche delle segnalazioni non arrivano in produzione, ma funzionano correttamente in locale.

## 🔍 Diagnosi

### 1. Test del Sistema di Notifiche
Esegui questi comandi in produzione per diagnosticare il problema:

```bash
# Test generale del sistema di notifiche
php artisan test:notifications

# Test specifico delle notifiche delle segnalazioni
php artisan test:report-notifications

# Test con creazione di una nuova segnalazione
php artisan test:report-notifications --create-report
```

### 2. Controllo Configurazione Broadcasting
Verifica che in produzione sia configurato correttamente:

```bash
# Controlla la configurazione di broadcasting
php artisan config:show broadcasting

# Controlla le variabili d'ambiente
php artisan config:show | grep -i broadcast
```

### 3. Controllo Code (Queue)
Le notifiche potrebbero essere in coda ma non processate:

```bash
# Controlla le code
php artisan queue:work --once

# Controlla i job falliti
php artisan queue:failed

# Pulisci i job falliti se necessario
php artisan queue:flush
```

### 4. Controllo Log
Controlla i log per errori:

```bash
# Controlla i log di Laravel
tail -f storage/logs/laravel.log

# Controlla i log di broadcasting
tail -f storage/logs/broadcast.log
```

## 🛠️ Soluzioni Comuni

### 1. Configurazione Broadcasting
Se usi **Reverb** (default), assicurati che sia configurato:

```env
BROADCAST_CONNECTION=reverb
REVERB_APP_KEY=your_key
REVERB_APP_SECRET=your_secret
REVERB_APP_ID=your_app_id
REVERB_HOST=your_host
REVERB_PORT=443
REVERB_SCHEME=https
```

Se usi **Pusher**:
```env
BROADCAST_CONNECTION=pusher
PUSHER_APP_KEY=your_key
PUSHER_APP_SECRET=your_secret
PUSHER_APP_ID=your_app_id
PUSHER_APP_CLUSTER=your_cluster
```

### 2. Configurazione Code
Assicurati che le code siano configurate e in esecuzione:

```env
QUEUE_CONNECTION=database
```

Esegui il worker delle code:
```bash
php artisan queue:work --daemon
```

### 3. Configurazione Redis (se usato)
Se usi Redis per le code:

```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 4. Permessi File
Assicurati che i permessi siano corretti:

```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

## 🔧 Soluzioni Alternative

### 1. Disabilitare Broadcasting Temporaneamente
Se il broadcasting non funziona, puoi disabilitarlo temporaneamente modificando `app/Models/Notification.php`:

```php
protected static function broadcastNotification(Notification $notification): void
{
    // Commenta temporaneamente per disabilitare broadcasting
    /*
    try {
        event(new \App\Events\ChatNotificationEvent($notification, 'created'));
    } catch (\Exception $e) {
        \Log::error('Failed to broadcast notification', [
            'notification_id' => $notification->id,
            'error' => $e->getMessage()
        ]);
    }
    */
    
    // Log invece di broadcast
    \Log::info('Notification created (broadcasting disabled)', [
        'notification_id' => $notification->id,
        'user_id' => $notification->user_id,
        'type' => $notification->type
    ]);
}
```

### 2. Usare Log Driver per Broadcasting
Cambia temporaneamente il driver di broadcasting a 'log':

```env
BROADCAST_CONNECTION=log
```

Questo salverà gli eventi nei log invece di inviarli via WebSocket.

### 3. Verificare Frontend
Assicurati che il frontend sia configurato per ricevere le notifiche:

- Controlla che Echo sia configurato correttamente
- Verifica che i canali privati siano autenticati
- Controlla la console del browser per errori JavaScript

## 📋 Checklist di Verifica

- [ ] `php artisan test:notifications` passa tutti i test
- [ ] `php artisan test:report-notifications` crea notifiche correttamente
- [ ] Le variabili d'ambiente sono configurate correttamente
- [ ] Le code sono in esecuzione (`php artisan queue:work`)
- [ ] Non ci sono job falliti (`php artisan queue:failed`)
- [ ] I log non mostrano errori di broadcasting
- [ ] Il frontend è configurato per ricevere notifiche
- [ ] I permessi dei file sono corretti

## 🆘 Se Niente Funziona

1. **Disabilita temporaneamente il broadcasting** (soluzione 1 sopra)
2. **Usa il driver 'log'** per broadcasting (soluzione 2 sopra)
3. **Controlla i log** per vedere se le notifiche vengono create
4. **Verifica manualmente** che le notifiche appaiano nel database

## 📞 Supporto

Se il problema persiste, fornisci:
- Output dei comandi di test
- Configurazione broadcasting (senza credenziali)
- Log degli errori
- Versione di Laravel e PHP
