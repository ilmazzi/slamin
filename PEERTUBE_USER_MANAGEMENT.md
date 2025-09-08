# Gestione Utenti PeerTube - Pannello Admin

## 📋 Panoramica

È stata implementata una funzionalità completa per la gestione degli utenti PeerTube nel pannello amministrativo. Questa funzionalità permette di:

- **Visualizzare** tutti gli utenti del sistema
- **Gestire** i dati PeerTube degli utenti
- **Creare** nuovi account PeerTube
- **Ricreare** account PeerTube esistenti
- **Modificare** manualmente i dati PeerTube
- **Monitorare** i log per il debug

## 🚀 Accesso alla Funzionalità

1. **Accedi** al pannello admin
2. **Vai** su "PeerTube" nella sidebar
3. **Clicca** su "Gestione Utenti" nella pagina di configurazione

**URL diretto**: `/admin/peertube/manage-users`

## 🎯 Funzionalità Principali

### 1. **Selezione Utente**
- **Dropdown** con tutti gli utenti del sistema
- **Indicatori visivi** per utenti con/senza account PeerTube
- **Filtro** per ruolo e stato PeerTube

### 2. **Visualizzazione Dati**
- **Dettagli utente** completi (nome, email, ruoli)
- **Stato account PeerTube** (presente/assente)
- **Data creazione** account PeerTube
- **Dati PeerTube** (User ID, Channel ID, Account ID, Username)

### 3. **Gestione Account**
- **Crea Account**: Crea un nuovo account PeerTube per l'utente
- **Ricrea Account**: Elimina e ricrea l'account PeerTube esistente
- **Aggiorna Dati**: Modifica manualmente i dati PeerTube
- **Verifica Esistenza**: Controlla se l'utente esiste su PeerTube con 4 metodi diversi
- **Sincronizza**: Aggiorna i dati dal server PeerTube
- **Collega Esistente**: Collega un account PeerTube esistente all'utente locale
- **Gestione Conflitti**: Risolve automaticamente conflitti di email con 3 opzioni

### 4. **Log Debug**
- **Log recenti** PeerTube visibili in tempo reale
- **Aggiornamento** manuale dei log
- **Filtro** automatico per log PeerTube

## 🔧 Utilizzo

### **Creare un Account PeerTube**

1. **Seleziona** un utente dal dropdown
2. **Clicca** "Carica Dati" per visualizzare le informazioni
3. **Clicca** "Crea Account" per creare l'account PeerTube
4. **Verifica** i dati nel form e nei log

### **Risolvere Problemi (es. Channel ID inesistente)**

1. **Seleziona** l'utente con problemi
2. **Carica** i dati utente
3. **Modifica** manualmente il Channel ID nel form
4. **Clicca** "Aggiorna Dati" per salvare
5. **Oppure** clicca "Ricrea Account" per ricreare tutto

### **Debug con i Log**

1. **Visualizza** i log nella sidebar destra
2. **Cerca** errori specifici (es. "Unknown 11 on this instance")
3. **Identifica** l'utente e il problema
4. **Correggi** manualmente o ricrea l'account

### **Verificare l'Esistenza Utente**

1. **Seleziona** l'utente dal dropdown
2. **Clicca** "Verifica Esistenza"
3. **Visualizza** i risultati in un modal dettagliato
4. **Controlla** quali metodi hanno successo:
   - **User ID**: Verifica tramite PeerTube User ID
   - **Username**: Verifica tramite username PeerTube
   - **Email**: Verifica tramite email utente
   - **Channel ID**: Verifica tramite Channel ID

### **Sincronizzare Dati PeerTube**

1. **Seleziona** l'utente dal dropdown
2. **Clicca** "Sincronizza"
3. **Sistema** cerca automaticamente l'utente su PeerTube
4. **Aggiorna** i dati nel database con le informazioni più recenti
5. **Risolve** automaticamente discrepanze tra database locale e PeerTube

### **Eliminare Utenti PeerTube**

1. **Seleziona** l'utente dal dropdown
2. **Clicca** "Elimina da PeerTube"
3. **Conferma** l'operazione (ATTENZIONE: operazione irreversibile)
4. **Sistema** elimina l'utente da PeerTube e resetta i dati locali
5. **Ora** puoi creare un nuovo account senza conflitti

#### **Quando Usare l'Eliminazione:**
- **Conflitti email**: Quando un utente PeerTube esistente blocca la creazione
- **Account corrotti**: Quando i dati PeerTube sono inconsistenti
- **Reset completo**: Quando vuoi ricominciare da zero per un utente

### **Eliminare Utente PeerTube**

1. **Seleziona** l'utente dal dropdown
2. **Clicca** "Elimina da PeerTube"
3. **Conferma** l'operazione (ATTENZIONE: operazione permanente!)
4. **Sistema** elimina l'utente da PeerTube
5. **Resetta** i dati PeerTube nel database locale
6. **Ora puoi** creare un nuovo account senza conflitti

## 📊 Statistiche Dashboard

La pagina mostra statistiche in tempo reale:

- **Totale Utenti**: Tutti gli utenti del sistema
- **Con Account PeerTube**: Utenti con account PeerTube attivo
- **Senza Account PeerTube**: Utenti senza account PeerTube
- **Copertura**: Percentuale di utenti con account PeerTube

## 🛠️ API Endpoints

### **GET** `/admin/peertube/manage-users`
- **Scopo**: Pagina principale di gestione utenti
- **Accesso**: Solo admin/moderator

### **POST** `/admin/peertube/show-user`
- **Scopo**: Ottiene dettagli di un utente specifico
- **Parametri**: `user_id` (integer)
- **Risposta**: JSON con dati utente e PeerTube

### **POST** `/admin/peertube/create-user-account`
- **Scopo**: Crea o ricrea account PeerTube
- **Parametri**: 
  - `user_id` (integer)
  - `force_recreate` (boolean, opzionale)
- **Risposta**: JSON con risultato operazione

### **PUT** `/admin/peertube/update-user-data`
- **Scopo**: Aggiorna dati PeerTube manualmente
- **Parametri**:
  - `user_id` (integer)
  - `peertube_username` (string, opzionale)
  - `peertube_channel_id` (integer, opzionale)
  - `peertube_account_id` (integer, opzionale)
- **Risposta**: JSON con risultato operazione

### **POST** `/admin/peertube/verify-user-exists`
- **Scopo**: Verifica se un utente esiste su PeerTube
- **Parametri**: `user_id` (integer)
- **Risposta**: JSON con risultati verifica per tutti i metodi (User ID, Username, Email, Channel ID)

### **POST** `/admin/peertube/sync-user-data`
- **Scopo**: Sincronizza i dati PeerTube dal server
- **Parametri**: `user_id` (integer)
- **Risposta**: JSON con dati aggiornati e risultati verifica

### **PUT** `/admin/peertube/change-user-email`
- **Scopo**: Cambia l'email di un utente per risolvere conflitti PeerTube
- **Parametri**: 
  - `user_id` (integer)
  - `new_email` (string, email unica)
- **Risposta**: JSON con risultato operazione

### **DELETE** `/admin/peertube/delete-user`
- **Scopo**: Elimina un utente da PeerTube per risolvere conflitti
- **Parametri**: 
  - `user_id` (integer)
  - `delete_by_email` (boolean, default: true)
- **Risposta**: JSON con risultato operazione

## 🔍 Risoluzione Problemi Comuni

### **Errore "Unknown 11 on this instance"**
- **Causa**: Channel ID non esiste su PeerTube
- **Soluzione**: Usa "Verifica Esistenza" per identificare il problema, poi "Sincronizza" o "Aggiorna Dati"

### **Conflitto Email "Esiste già un utente PeerTube con questa email"**
- **Causa**: Email già utilizzata da un altro utente PeerTube
- **Soluzioni disponibili**:
  1. **Collega Esistente**: Collega l'account PeerTube esistente all'utente locale
  2. **Forza Ricreazione**: Elimina l'account esistente e ne crea uno nuovo
  3. **Elimina Utente**: Elimina permanentemente l'utente PeerTube esistente
  4. **Cambia Email**: Modifica l'email dell'utente locale per evitare conflitti

#### **Come Risolvere il Conflitto Email:**

1. **Seleziona** l'utente dal dropdown
2. **Clicca** "Crea Account"
3. **Sistema** rileva il conflitto e mostra un modal con opzioni
4. **Scegli** una delle quattro soluzioni:
   - **Collega Esistente**: Più sicuro, mantiene i dati esistenti
   - **Forza Ricreazione**: Distruttivo, elimina l'account esistente e ne crea uno nuovo
   - **Elimina Utente**: Elimina permanentemente l'utente PeerTube esistente
   - **Cambia Email**: Richiede nuova verifica email per l'utente
  1. Vai su Gestione Utenti
  2. Trova l'utente con Channel ID 11
  3. Modifica il Channel ID o ricrea l'account

### **Account PeerTube non creato**
- **Causa**: Errore durante la creazione automatica
- **Soluzione**:
  1. Vai su Gestione Utenti
  2. Seleziona l'utente
  3. Clicca "Crea Account" manualmente

### **Dati PeerTube mancanti**
- **Causa**: Sincronizzazione incompleta
- **Soluzione**:
  1. Vai su Gestione Utenti
  2. Seleziona l'utente
  3. Clicca "Ricrea Account" per sincronizzare

## 📝 Log e Debug

### **Log Visibili**
- **Creazione account** PeerTube
- **Errori** di connessione
- **Aggiornamenti** dati utente
- **Operazioni** amministrative

### **Formato Log**
```
[2025-09-08 21:04:57] local.ERROR: Errore Guzzle upload video PeerTube {"user_id":4,"status_code":400,"response_body":"Unknown 11 on this instance"}
```

### **Ricerca Log**
- **Filtro automatico** per "PeerTube" e "peertube"
- **Ultimi 20 log** PeerTube visibili
- **Aggiornamento** manuale disponibile

## 🔐 Sicurezza

- **Accesso limitato** a admin e moderator
- **Validazione** input su tutti gli endpoint
- **Logging** completo di tutte le operazioni
- **CSRF protection** su tutti i form

## 🎨 Interfaccia

- **Design responsive** mobile-first
- **Componenti template** nativi
- **Feedback visivo** per tutte le operazioni
- **Alert** per successo/errore
- **Loading states** per operazioni asincrone

## 📱 Compatibilità

- **Desktop**: Layout completo con sidebar log
- **Mobile**: Layout ottimizzato per schermi piccoli
- **Browser**: Supporto moderni (Chrome, Firefox, Safari, Edge)

---

**Nota**: Questa funzionalità è stata progettata per risolvere problemi come l'errore "Unknown 11 on this instance" e permettere la gestione manuale degli account PeerTube quando necessario.
