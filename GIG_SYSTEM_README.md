# Sistema di Gestione Gig e Eventi

## Panoramica

Questo sistema gestisce la creazione, gestione e sincronizzazione automatica tra eventi e gig (posizioni d'ingaggio). Quando si crea un evento con posizioni d'ingaggio, vengono automaticamente generati gig corrispondenti nella sezione dedicata.

## Funzionalità Principali

### 1. Sincronizzazione Automatica Eventi ↔ Gig
- **Creazione Evento**: Le posizioni d'ingaggio vengono automaticamente convertite in gig
- **Modifica Evento**: I gig vengono aggiornati quando si modificano le posizioni
- **Eliminazione Evento**: Tutti i gig correlati vengono eliminati automaticamente

### 2. Gestione Candidature
- Visualizzazione del numero di candidati per ogni gig
- Link diretto alla lista dei candidati
- Accettazione/rifiuto delle candidature
- Chiusura automatica dei gig quando tutte le posizioni sono occupate

### 3. Sistema di Notifiche Completo
- Nuove candidature
- Accettazione/rifiuto candidature
- Chiusura/apertura gig
- Condivisione gig
- Messaggi globali agli utenti non-audience

### 4. Eliminazione Completa degli Eventi
Quando si elimina un evento, vengono eliminati automaticamente:
- ✅ Tutti i gig associati
- ✅ Tutte le candidature ai gig
- ✅ Tutte le notifiche correlate (evento e gig)

## Architettura Tecnica

### Observer Pattern
- **EventObserver**: Gestisce la sincronizzazione automatica eventi ↔ gig
- **GigApplicationObserver**: Mantiene aggiornati i contatori delle candidature

### Modelli Principali
- `Event`: Eventi con posizioni d'ingaggio (campo `gig_positions`)
- `Gig`: Posizioni d'ingaggio individuali
- `GigApplication`: Candidature ai gig
- `Notification`: Sistema centralizzato di notifiche

### Relazioni
```php
Event -> hasMany(Gig)
Gig -> hasMany(GigApplication)
Gig -> belongsTo(Event)
GigApplication -> belongsTo(Gig)
GigApplication -> belongsTo(User)
```

## Comandi Artisan Disponibili

### Sincronizzazione
```bash
# Sincronizza tutti gli eventi con posizioni d'ingaggio
php artisan events:sync-gig-positions

# Sincronizza un evento specifico
php artisan events:sync-gig-positions {event_id}
```

### Gestione Gig
```bash
# Sincronizza manualmente i contatori delle candidature
php artisan gigs:update-counts

# Testa il sistema di notifiche per i gig
php artisan gigs:test-notifications
```

### Test Eliminazione
```bash
# Testa l'eliminazione completa di un evento
php artisan events:test-deletion {event_id}
```

## Flusso di Lavoro

### 1. Creazione Evento con Posizioni
1. Crea un evento tramite il form
2. Aggiungi posizioni d'ingaggio (tipo, quantità, compenso, ecc.)
3. Salva l'evento
4. **Automaticamente**: Vengono creati i gig corrispondenti

### 2. Gestione Candidature
1. Gli utenti vedono i gig nella sezione `/gigs`
2. Possono candidarsi ai gig aperti
3. L'organizzatore vede il numero di candidati
4. Può accettare/rifiutare candidature
5. I gig si chiudono automaticamente quando tutte le posizioni sono occupate

### 3. Eliminazione Evento
1. Elimina l'evento
2. **Automaticamente**: Vengono eliminati tutti i gig, candidature e notifiche correlate

## Struttura Dati

### Campo `gig_positions` (JSON)
```json
[
  {
    "title": "Poeta Slam",
    "description": "Cerca poeti per performance",
    "type": "poetry_slam",
    "quantity": 4,
    "cachet_amount": 100,
    "cachet_currency": "EUR",
    "language": "italian",
    "requirements": "Esperienza minima 2 anni"
  }
]
```

### Mappatura Tipi Posizione → Gig
- `poetry_slam` → `artist_poet`
- `mc` → `mc_guest`
- `technical` → `technical_support`
- `volunteer` → `volunteer`

## Sicurezza e Autorizzazioni

- Solo gli organizzatori possono gestire le candidature
- Gli utenti "audience" non possono candidarsi
- Controlli di autorizzazione su tutte le azioni
- Validazione dei dati in input

## Performance

- Contatori delle candidature aggiornati automaticamente
- Indici database ottimizzati
- Query efficienti con eager loading
- Logging per debugging e monitoraggio

## Troubleshooting

### Gig non visibili dopo creazione evento
```bash
php artisan events:sync-gig-positions
```

### Contatori candidature non aggiornati
```bash
php artisan gigs:update-counts
```

### Test eliminazione evento
```bash
php artisan events:test-deletion {event_id}
```

## Log e Monitoraggio

Tutti gli eventi critici vengono loggati:
- Creazione/aggiornamento gig
- Eliminazione eventi e dipendenze
- Errori di sincronizzazione
- Operazioni di candidatura

## Estensioni Future

- Notifiche email automatiche
- Dashboard analytics per organizzatori
- Sistema di rating e feedback
- Integrazione con calendari esterni
- API REST per integrazioni terze 
