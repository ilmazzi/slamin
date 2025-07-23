# 🎠 Carousel con Contenuti Esistenti

## 📋 Panoramica

La nuova funzionalità permette agli amministratori di selezionare contenuti esistenti dal sito (video, eventi, utenti, snap) e includerli nel carousel della home page, oltre alla possibilità tradizionale di caricare file personalizzati.

## ✨ Funzionalità

### 🔄 Due Modalità di Creazione

1. **📤 Upload Tradizionale**
   - Caricamento di immagini/video personalizzati
   - Titolo e descrizione personalizzati
   - Link personalizzati

2. **🔍 Contenuti Esistenti**
   - Selezione da contenuti già presenti sul sito
   - Ricerca intelligente per tipo di contenuto
   - Personalizzazione opzionale del titolo/descrizione
   - Aggiornamento automatico della cache

### 📊 Tipi di Contenuto Supportati

| Tipo | Descrizione | Criteri di Ricerca |
|------|-------------|-------------------|
| **Video** | Performance di Poetry Slam | Titolo, descrizione, utente, visualizzazioni |
| **Eventi** | Eventi Poetry Slam | Titolo, descrizione, organizzatore, data, città |
| **Utenti** | Profili di poeti/artisti | Nome, nickname, email, numero video |
| **Snap** | Momenti salienti dei video | Titolo, descrizione, utente, like, timestamp |

## 🛠️ Implementazione Tecnica

### Database

```sql
-- Nuovi campi aggiunti alla tabella carousels
ALTER TABLE carousels ADD COLUMN content_type VARCHAR(50) NULL;
ALTER TABLE carousels ADD COLUMN content_id BIGINT UNSIGNED NULL;
ALTER TABLE carousels ADD COLUMN content_title VARCHAR(255) NULL;
ALTER TABLE carousels ADD COLUMN content_description TEXT NULL;
ALTER TABLE carousels ADD COLUMN content_image_url VARCHAR(500) NULL;
ALTER TABLE carousels ADD COLUMN content_url VARCHAR(500) NULL;

-- Indici per performance
CREATE INDEX idx_carousels_content ON carousels(content_type, content_id);
CREATE INDEX idx_carousels_content_type ON carousels(content_type);
```

### Modello Carousel

```php
// Nuovi metodi aggiunti
public function isContentReference()
public function getReferencedContent()
public function updateContentCache()
public function getDisplayTitleAttribute()
public function getDisplayDescriptionAttribute()
public function getDisplayUrlAttribute()
```

### Controller

```php
// Nuovi metodi aggiunti
public function searchContent(Request $request)
protected function getContentById($type, $id)
```

## 🎯 Utilizzo

### 1. Accesso alla Funzionalità

1. Vai su **Admin → Carosello**
2. Clicca **"Nuova Slide"**
3. Scegli tra i due tab:
   - **"Carica File"** (tradizionale)
   - **"Contenuti Esistenti"** (nuovo)

### 2. Selezione Contenuti Esistenti

1. **Seleziona Tipo**: Video, Eventi, Utenti, Snap
2. **Cerca Contenuto**: Digita per filtrare i risultati
3. **Seleziona**: Clicca il pulsante ✓ accanto al contenuto desiderato
4. **Personalizza** (opzionale): Modifica titolo, descrizione, link
5. **Configura**: Imposta ordine, date, stato
6. **Crea**: Salva la slide

### 3. Gestione Cache

La cache dei contenuti referenziati si aggiorna automaticamente, ma puoi forzare l'aggiornamento:

```bash
php artisan carousels:update-cache
```

## 🔍 Ricerca e Filtri

### Video
- **Criteri**: Titolo, descrizione
- **Ordinamento**: Visualizzazioni (decrescente)
- **Filtri**: Solo video approvati e pubblici

### Eventi
- **Criteri**: Titolo, descrizione
- **Ordinamento**: Data (più recenti)
- **Filtri**: Solo eventi pubblicati

### Utenti
- **Criteri**: Nome, nickname, email
- **Ordinamento**: Numero video (decrescente)
- **Info**: Località, foto profilo

### Snap
- **Criteri**: Titolo, descrizione
- **Ordinamento**: Like (decrescente)
- **Info**: Video di riferimento, timestamp

## 🎨 Interfaccia Utente

### Tab "Contenuti Esistenti"

```
┌─────────────────────────────────────────────────────────┐
│ Tipo di Contenuto: [Video ▼]                            │
├─────────────────────────────────────────────────────────┤
│ Cerca Contenuto: [________________] [🔍]                │
├─────────────────────────────────────────────────────────┤
│ Risultati Ricerca:                                      │
│ ┌─────────────────────────────────────────────────────┐ │
│ │ [🖼️] Titolo Video                    [✓] Seleziona │ │
│ │     Descrizione breve...                            │ │
│ │     Utente • 150 visualizzazioni                   │ │
│ └─────────────────────────────────────────────────────┘ │
├─────────────────────────────────────────────────────────┤
│ Contenuto Selezionato:                                  │
│ ┌─────────────────────────────────────────────────────┐ │
│ │ [🖼️] Titolo del contenuto selezionato              │ │
│ │     Descrizione del contenuto...                    │ │
│ └─────────────────────────────────────────────────────┘ │
├─────────────────────────────────────────────────────────┤
│ Personalizza (Opzionale):                              │
│ ┌─────────────────────────────────────────────────────┐ │
│ │ Titolo Personalizzato: [________________]           │ │
│ │ Descrizione: [________________]                     │ │
│ │ URL Link: [________________]                        │ │
│ │ Testo Link: [________________]                      │ │
│ └─────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────┘
```

### Indicatori Visivi

- **Badge**: Mostra il tipo di contenuto (Video, Evento, Utente, Snap)
- **Icona Link**: Indica contenuti referenziati vs upload
- **Preview**: Anteprima del contenuto selezionato

## 🔧 Comandi Utili

### Test Ricerca
```bash
# Testa la ricerca per tipo di contenuto
php artisan carousels:test-search video
php artisan carousels:test-search event
php artisan carousels:test-search user
php artisan carousels:test-search snap

# Con query specifica
php artisan carousels:test-search video "poetry"
```

### Gestione Cache
```bash
# Aggiorna cache contenuti referenziati
php artisan carousels:update-cache

# Controlla stato carousel
php artisan carousels:check
```

## 🚀 Vantaggi

### Per gli Amministratori
- ✅ **Flessibilità**: Scegli tra upload e contenuti esistenti
- ✅ **Efficienza**: Riutilizza contenuti di qualità
- ✅ **Aggiornamento Automatico**: Cache sempre aggiornata
- ✅ **Personalizzazione**: Override opzionale di titoli/descrizioni

### Per gli Utenti
- ✅ **Contenuti Rilevanti**: Carousel con contenuti del sito
- ✅ **Scoperta**: Esposizione di contenuti di qualità
- ✅ **Engagement**: Link diretti ai contenuti

### Per il Sistema
- ✅ **Performance**: Cache per ridurre query
- ✅ **Scalabilità**: Supporto per nuovi tipi di contenuto
- ✅ **Manutenibilità**: Codice modulare e ben strutturato

## 🔮 Sviluppi Futuri

### Possibili Estensioni
- **Articoli/Blog**: Contenuti testuali
- **Gallerie**: Raccolte di immagini
- **Playlist**: Gruppi di video correlati
- **Categorie**: Filtri per tipo di contenuto
- **Analytics**: Statistiche di utilizzo carousel

### Miglioramenti
- **Ricerca Avanzata**: Filtri multipli
- **Preview Live**: Anteprima in tempo reale
- **Bulk Operations**: Selezione multipla
- **Scheduling**: Programmazione automatica
- **A/B Testing**: Test di diverse configurazioni

## 📝 Note Tecniche

### Sicurezza
- Validazione input per tutti i tipi di contenuto
- Controllo permessi per contenuti privati
- Sanitizzazione output HTML

### Performance
- Query ottimizzate con eager loading
- Cache intelligente per ridurre carico database
- Indici appropriati per ricerche veloci

### Compatibilità
- Backward compatibility con carousel esistenti
- Migrazione automatica dei dati
- API RESTful per integrazioni future

---

**🎯 Obiettivo Raggiunto**: Il carousel ora supporta sia contenuti personalizzati che contenuti esistenti del sito, offrendo massima flessibilità agli amministratori e migliorando l'esperienza utente. 
