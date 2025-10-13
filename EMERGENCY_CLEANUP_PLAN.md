# 🚨 PIANO DI EMERGENZA - PULIZIA TRADUZIONI

## 🔥 SITUAZIONE CRITICA IDENTIFICATA

### ❌ PROBLEMI GRAVISSIMI:
- **287 chiavi duplicate** (10% del totale)
- **`title` in 15 file diversi** 
- **`cancel` in 10 file**
- **18 file backup sparsi**
- **File sovradimensionati** (events.php: 807 chiavi)

### 🎯 OBIETTIVI IMMEDIATI:
1. **Eliminare TUTTE le chiavi duplicate**
2. **Rimuovere TUTTI i file backup**
3. **Riorganizzare file sovradimensionati**
4. **Standardizzare naming convention**

---

## 📋 FASE 1: PULIZIA IMMEDIATA

### 1.1 Rimozione File Backup
```bash
find lang/it -name "*.backup*" -delete
```

### 1.2 Consolidamento Chiavi Comuni
Creare file `common.php` con:
- `title`, `delete`, `view`, `edit`, `cancel`, `save`, `close`
- `actions`, `status`, `comments`, `preview`
- `search_placeholder`, `description`, `dashboard`

### 1.3 Riorganizzazione File Grandi
- `events.php` (807 chiavi) → Dividere in:
  - `events.php` (generale)
  - `events_management.php` (admin)
  - `events_scoring.php` (gamification)

- `admin.php` (504 chiavi) → Dividere in:
  - `admin.php` (generale)
  - `admin_users.php` (gestione utenti)
  - `admin_content.php` (gestione contenuti)

---

## 📋 FASE 2: STANDARDIZZAZIONE

### 2.1 Naming Convention
- **File specifici:** `{feature}.php`
- **Chiavi specifiche:** `{feature}_{action}`
- **Chiavi comuni:** `{action}` (solo in common.php)

### 2.2 Struttura Logica
```
lang/it/
├── common.php           (chiavi universali)
├── admin.php           (admin generale)
├── admin_users.php     (gestione utenti)
├── admin_content.php   (gestione contenuti)
├── events.php          (eventi generali)
├── events_management.php (gestione eventi)
├── events_scoring.php  (scoring eventi)
├── forum.php          (forum generale)
├── forum_moderation.php (moderazione)
└── ... (altri file specifici)
```

---

## 📋 FASE 3: IMPLEMENTAZIONE

### 3.1 Script di Migrazione
- Backup completo prima delle modifiche
- Script per spostare chiavi duplicate
- Aggiornamento riferimenti nel codice

### 3.2 Validazione
- Test di tutte le pagine
- Verifica traduzioni mancanti
- Controllo performance

---

## 🎯 RISULTATI ATTESI

### Prima (ATTUALE):
- 32 file, 2,836 chiavi, 287 duplicate
- File sovradimensionati
- Backup sparsi

### Dopo (OBIETTIVO):
- ~25 file, ~2,500 chiavi, 0 duplicate
- File dimensioni ragionevoli
- Zero backup files
- Struttura logica e mantenibile

---

## ⚠️ RISCHI E MITIGAZIONI

### RISCHI:
- Rottura di pagine esistenti
- Perdita di traduzioni
- Tempo di implementazione

### MITIGAZIONI:
- Backup completo prima di iniziare
- Test su ambiente di sviluppo
- Migrazione graduale
- Validazione continua

---

## 🚀 PROSSIMI PASSI

1. **Conferma del piano** da parte dell'utente
2. **Backup completo** del sistema
3. **Implementazione graduale** delle modifiche
4. **Test e validazione** continua
5. **Deploy in produzione** dopo test completi
