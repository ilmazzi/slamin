# 📊 Sistema di Logging Completo - Slamin

## 🎯 Panoramica

Il sistema di logging implementato in Slamin registra automaticamente tutte le operazioni del sito in inglese, fornendo agli amministratori una visione completa dell'attività del sistema.

## 🏗️ Architettura del Sistema

### Componenti Principali

1. **Model ActivityLog** (`app/Models/ActivityLog.php`)
   - Tabella `activity_logs` nel database
   - Relazioni con utenti e modelli correlati
   - Metodi helper per filtraggio e formattazione

2. **Service LoggingService** (`app/Services/LoggingService.php`)
   - Servizio centralizzato per il logging
   - Metodi specializzati per diverse categorie di operazioni
   - Gestione automatica degli errori

3. **Middleware LoggingMiddleware** (`app/Http/Middleware/LoggingMiddleware.php`)
   - Logging automatico di tutte le richieste HTTP
   - Filtraggio intelligente per evitare spam
   - Raccolta di metadati (IP, User Agent, tempo di risposta)

4. **Controller LogController** (`app/Http/Controllers/Admin/LogController.php`)
   - Interfaccia admin per visualizzare i log
   - Filtri avanzati e statistiche
   - Esportazione CSV e pulizia automatica

## 📋 Categorie di Log

### 🔐 Authentication
- Login/Logout
- Registrazione utenti
- Tentativi di login falliti
- Reset password

### 👥 Users
- Creazione/modifica profili
- Aggiornamento foto profilo
- Cambio password
- Gestione ruoli

### 🎪 Events
- Creazione/modifica eventi
- Inviti e richieste di partecipazione
- Pubblicazione/archiviazione

### 🎬 Videos
- Upload video
- Visualizzazioni e interazioni
- Commenti e like
- Gestione contenuti

### ⚙️ Admin
- Accesso al pannello admin
- Gestione impostazioni
- Operazioni di sistema
- Gestione permessi

### 💰 Premium
- Sottoscrizioni
- Pagamenti
- Gestione abbonamenti

### 🔧 System
- Operazioni di manutenzione
- Errori e warning
- Performance del sistema

## 🚀 Utilizzo

### Per gli Amministratori

#### Accesso ai Log
1. Accedere al pannello admin
2. Navigare su "System Logs" nel menu laterale
3. Visualizzare i log con filtri e statistiche

#### Filtri Disponibili
- **Data**: Range di date personalizzabile
- **Categoria**: Filtro per tipo di operazione
- **Livello**: Info, Warning, Error, Critical
- **Utente**: Filtro per utente specifico
- **Ricerca**: Testo libero in descrizione, azione, IP
- **Status Code**: Filtro per codice di risposta HTTP

#### Funzionalità Avanzate
- **Esportazione CSV**: Download dei log filtrati
- **Pulizia Automatica**: Rimozione log vecchi
- **Statistiche**: Dashboard con metriche in tempo reale
- **Dettagli Log**: Visualizzazione completa di ogni entry

### Per gli Sviluppatori

#### Logging Manuale
```php
use App\Services\LoggingService;

// Log di autenticazione
LoggingService::logAuth('login', [
    'user_id' => $user->id,
    'ip' => $request->ip()
]);

// Log di operazioni utente
LoggingService::logUser('update', [
    'changes' => $changes
], 'App\Models\User', $user->id);

// Log di eventi
LoggingService::logEvent('create', [
    'event_title' => $event->title
], 'App\Models\Event', $event->id);

// Log di errori
LoggingService::logError('database_error', [
    'error' => $e->getMessage()
]);
```

#### Utilizzo del Trait Loggable
```php
use App\Traits\Loggable;

class MyController extends Controller
{
    use Loggable;

    public function store(Request $request)
    {
        try {
            $model = Model::create($request->validated());
            
            $this->logSuccess('create', [
                'data' => $request->validated()
            ], get_class($model), $model->id);
            
            return redirect()->back();
        } catch (\Exception $e) {
            $this->logError('create_failed', [
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
}
```

## 🛠️ Comandi Artisan

### Generazione Log di Test
```bash
# Genera 10 log di test
php artisan test:logging

# Genera un numero specifico di log
php artisan test:logging --count=50
```

### Pulizia Log di Test
```bash
# Rimuove tutti i log di test
php artisan logs:clear-test

# Rimuove senza conferma
php artisan logs:clear-test --confirm
```

### Pulizia Log Vecchi
```bash
# Rimuove log più vecchi di 30 giorni
php artisan admin:logs:clear --days=30

# Rimuove solo log di una categoria specifica
php artisan admin:logs:clear --days=30 --category=authentication
```

## 📊 Statistiche Disponibili

### Dashboard Principale
- **Totale Log**: Numero complessivo di log
- **Log Oggi**: Log generati oggi
- **Log Questa Settimana**: Log degli ultimi 7 giorni
- **Tasso di Errore**: Percentuale di errori sul totale
- **Tempo di Risposta Medio**: Performance del sistema

### Distribuzioni
- **Per Livello**: Info, Warning, Error, Critical
- **Per Categoria**: Authentication, Events, Users, etc.
- **Per Utente**: Top 5 utenti più attivi
- **Per Status Code**: Distribuzione codici HTTP

## 🔒 Sicurezza e Privacy

### Dati Esclusi dal Logging
- Password e token di autenticazione
- Dati sensibili personali
- Informazioni di pagamento
- Token CSRF

### Retention Policy
- Log mantenuti per 30 giorni di default
- Possibilità di configurare retention personalizzata
- Pulizia automatica configurabile

### Accesso ai Log
- Solo amministratori e moderatori
- Logging di tutti gli accessi ai log
- Audit trail completo

## 📈 Monitoraggio e Alerting

### Metriche Chiave
- **Error Rate**: Tasso di errori > 5%
- **Response Time**: Tempo di risposta > 2 secondi
- **Failed Logins**: Tentativi di login falliti
- **Admin Actions**: Operazioni amministrative

### Alerting Suggerito
- Email per errori critici
- Notifiche per tentativi di accesso sospetti
- Report giornalieri per amministratori

## 🔧 Configurazione

### Variabili d'Ambiente
```env
# Abilita/disabilita logging
LOGGING_ENABLED=true

# Livello di log minimo
LOG_LEVEL=info

# Retention giorni
LOG_RETENTION_DAYS=30

# Abilita logging middleware
LOG_MIDDLEWARE_ENABLED=true
```

### Personalizzazione
- Aggiungere nuove categorie in `ActivityLog::getCategories()`
- Personalizzare descrizioni in `LoggingService`
- Configurare filtri nel middleware
- Estendere statistiche nel controller

## 🚨 Troubleshooting

### Problemi Comuni

#### Log non vengono generati
1. Verificare che la tabella `activity_logs` esista
2. Controllare i permessi del database
3. Verificare che il middleware sia registrato

#### Performance lente
1. Aggiungere indici al database
2. Configurare pulizia automatica
3. Filtrare log non necessari nel middleware

#### Errori di memoria
1. Ridurre retention dei log
2. Implementare log rotation
3. Ottimizzare query di filtraggio

### Debug
```bash
# Verifica stato del sistema
php artisan logs:status

# Test connessione database
php artisan logs:test-db

# Verifica middleware
php artisan logs:test-middleware
```

## 📚 Esempi Pratici

### Logging in EventController
```php
public function store(Request $request)
{
    try {
        $event = Event::create($request->validated());
        
        LoggingService::logEvent('create', [
            'event_id' => $event->id,
            'title' => $event->title,
            'organizer_id' => $event->organizer_id
        ], 'App\Models\Event', $event->id);
        
        return redirect()->route('events.show', $event);
    } catch (\Exception $e) {
        LoggingService::logError('event_create_failed', [
            'error' => $e->getMessage(),
            'data' => $request->validated()
        ]);
        
        throw $e;
    }
}
```

### Logging in ProfileController
```php
public function update(Request $request)
{
    $user = Auth::user();
    $changes = $request->only(['name', 'email', 'bio']);
    
    try {
        $user->update($changes);
        
        LoggingService::logUser('update', [
            'changes' => $changes,
            'user_id' => $user->id
        ], 'App\Models\User', $user->id);
        
        return redirect()->back()->with('success', 'Profile updated');
    } catch (\Exception $e) {
        LoggingService::logError('profile_update_failed', [
            'error' => $e->getMessage(),
            'user_id' => $user->id
        ]);
        
        throw $e;
    }
}
```

## 🎉 Conclusioni

Il sistema di logging implementato fornisce:

✅ **Visibilità Completa**: Tutte le operazioni del sito sono tracciate
✅ **Sicurezza**: Monitoraggio di accessi e tentativi sospetti  
✅ **Performance**: Metriche e tempi di risposta
✅ **Debugging**: Tracciamento dettagliato degli errori
✅ **Compliance**: Audit trail per requisiti legali
✅ **Scalabilità**: Architettura ottimizzata per grandi volumi

Il sistema è pronto per l'uso in produzione e può essere facilmente esteso per nuove funzionalità. 