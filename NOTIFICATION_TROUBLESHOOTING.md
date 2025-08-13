# 🔧 Troubleshooting Notifiche in Produzione

## 🚨 Problema
Le notifiche delle segnalazioni non arrivano in produzione, ma funzionano correttamente in locale.

## 🔍 Diagnosi

### 1. Test del Sistema di Notifiche
Esegui questi comandi in produzione per diagnosticare il problema:

```bash
# Test generale del sistema di notifiche
php artisan test:like-notifications

# Test del sistema di broadcasting
php artisan test:broadcasting

# Test del sistema di logging
php artisan test:logging

# Controlla le notifiche nel database
php artisan tinker --execute="echo 'Notifiche totali: ' . \App\Models\Notification::count() . PHP_EOL; echo 'Notifiche non lette: ' . \App\Models\Notification::unread()->count() . PHP_EOL;"
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

### 5. Test Manuale Notifiche Segnalazioni
Per testare manualmente le notifiche delle segnalazioni:

```bash
# Controlla le segnalazioni esistenti
php artisan tinker --execute="echo 'Segnalazioni totali: ' . \App\Models\Report::count() . PHP_EOL; echo 'Segnalazioni in attesa: ' . \App\Models\Report::pending()->count() . PHP_EOL;"

# Test creazione notifica per una segnalazione esistente
php artisan tinker --execute="
\$report = \App\Models\Report::with(['reportable', 'user'])->first();
if (\$report && \$report->reportable && \$report->reportable->user) {
    \App\Models\Notification::createContentReportedNotification(\$report);
    echo 'Notifica creata per segnalazione ID: ' . \$report->id . PHP_EOL;
} else {
    echo 'Nessuna segnalazione valida trovata' . PHP_EOL;
}
"
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

- [ ] `php artisan test:like-notifications` passa tutti i test
- [ ] `php artisan test:broadcasting` funziona correttamente
- [ ] `php artisan test:logging` non mostra errori
- [ ] Le notifiche vengono create nel database
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

## ⚡ Soluzione Rapida

Se hai bisogno di una soluzione immediata, esegui questo comando per disabilitare temporaneamente il broadcasting:

```bash
# Backup del file originale
cp app/Models/Notification.php app/Models/Notification.php.backup

# Modifica temporanea per disabilitare broadcasting
sed -i 's/event(new \\App\\Events\\ChatNotificationEvent($notification, '\''created'\''));/\/\/ event(new \\App\\Events\\ChatNotificationEvent($notification, '\''created'\''));/' app/Models/Notification.php
```

Questo disabiliterà il broadcasting e le notifiche verranno solo salvate nel database e nei log.

## 📞 Supporto

Se il problema persiste, fornisci:
- Output dei comandi di test
- Configurazione broadcasting (senza credenziali)
- Log degli errori
- Versione di Laravel e PHP
