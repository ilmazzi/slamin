# 📊 ANALISI COMPLETA SEZIONE GIGS - REFACTOR LIVEWIRE

## 🎯 OBIETTIVO
Migrare completamente la sezione GIGS da Controller tradizionale a Livewire 3, seguendo le best practices.

---

## 📁 STRUTTURA ATTUALE

### Models (3)
1. **`Gig.php`** (340 righe)
   - Relazioni: User, Event, Group, GigApplication, Poem, PoemTranslation
   - Scopes: open, closed, urgent, featured, remote, byCategory, byType, byLocation, translationGigs, eventGigs
   - Accessors: status, is_expired, days_until_deadline, can_apply
   - Metodi: canUserApply(), areAllPositionsFilled(), shouldBeClosed(), close(), reopen(), canBeEditedBy(), canBeViewedBy(), share()

2. **`GigApplication.php`**
   - Statuses: pending, accepted, rejected, withdrawn
   - Relazioni: gig, user

3. **`GigPosition.php`**
   - Gestisce i tipi di posizioni disponibili per i gigs

### Controller (2)
1. **`GigController.php`** (761 righe) - 19 metodi:
   - **Public Pages:**
     - `index()` - Lista gigs pubblici con filtri (158 righe)
     - `show()` - Dettagli gig singolo (25 righe)
   
   - **CRUD Operations:**
     - `create()` - Form creazione (45 righe)
     - `store()` - Salva nuovo gig (54 righe)
     - `edit()` - Form modifica (31 righe)
     - `update()` - Aggiorna gig (41 righe)
     - `destroy()` - Elimina gig (24 righe)
   
   - **User Gigs:**
     - `myGigs()` - I miei gigs (73 righe)
     - `myApplications()` - Le mie candidature (55 righe)
   
   - **Applications Management:**
     - `manageApplications()` - Gestisci candidature (19 righe)
     - `apply()` - Candidati a un gig (53 righe)
     - `acceptApplication()` - Accetta candidatura (37 righe)
     - `rejectApplication()` - Rifiuta candidatura (27 righe)
     - `withdrawApplication()` - Ritira candidatura (26 righe)
   
   - **Gig Actions:**
     - `close()` - Chiudi gig (22 righe)
     - `reopen()` - Riapri gig (22 righe)
     - `sendGlobalMessage()` - Messaggio globale (33 righe)
     - `share()` - Condividi gig (13 righe)

2. **`Admin/GigPositionController.php`**
   - CRUD per gestione posizioni (admin only)

### Views (11 file)
1. **`index.blade.php`** (643 righe) - Lista gigs pubblici
   - Statistiche con cards
   - Filtri avanzati (categoria, tipo, lingua, location, featured, urgent, remote)
   - Lista gigs con paginazione
   - Search bar
   - Sort options

2. **`show.blade.php`** (431 righe) - Dettaglio gig
   - Info gig completo
   - Form candidatura
   - Lista candidature (se owner)
   - Azioni (edit, delete, close, reopen, share)

3. **`create.blade.php`** (219 righe) - Creazione gig
   - Form completo con tutti i campi
   - Select per event/group
   - Opzioni remote/urgent/featured

4. **`edit.blade.php`** (261 righe) - Modifica gig
   - Simile a create con campi precompilati

5. **`my-gigs.blade.php`** (379 righe) - I miei gigs
   - Lista gigs creati dall'utente
   - Filtri per status
   - Quick actions

6. **`my-applications.blade.php`** (184 righe) - Le mie candidature
   - Lista candidature inviate
   - Status per ogni candidatura
   - Azione ritira

7. **`manage-applications.blade.php`** (515 righe) - Gestisci candidature
   - Lista candidature ricevute
   - Accept/Reject per ogni candidatura
   - Invio messaggi

8. **File di test/backup:**
   - `index-original.blade.php` (777 righe) - Versione originale
   - `index-simple.blade.php` (21 righe) - Versione semplificata
   - `index-backup.blade.php` (1 riga) - Backup vuoto
   - `test.blade.php` (42 righe) - Test

### Routes
```php
Route::prefix('gigs')->name('gigs.')->group(function () {
    // Public
    Route::get('/', [GigController::class, 'index'])->name('index');
    Route::get('/create', [GigController::class, 'create'])->name('create')->middleware('auth');
    Route::post('/', [GigController::class, 'store'])->name('store')->middleware('auth');
    
    // Auth Required
    Route::middleware('auth')->group(function () {
        Route::get('/my-gigs', [GigController::class, 'myGigs'])->name('my-gigs');
        Route::get('/my-applications', [GigController::class, 'myApplications'])->name('my-applications');
        
        // Single Gig
        Route::get('/{gig}', [GigController::class, 'show'])->name('show');
        Route::get('/{gig}/edit', [GigController::class, 'edit'])->name('edit');
        Route::put('/{gig}', [GigController::class, 'update'])->name('update');
        Route::delete('/{gig}', [GigController::class, 'destroy'])->name('destroy');
        
        // Applications
        Route::get('/{gig}/applications', [GigController::class, 'manageApplications'])->name('manage-applications');
        Route::post('/{gig}/apply', [GigController::class, 'apply'])->name('apply');
        
        // Actions
        Route::post('/{gig}/close', [GigController::class, 'close'])->name('close');
        Route::post('/{gig}/reopen', [GigController::class, 'reopen'])->name('reopen');
        Route::post('/{gig}/share', [GigController::class, 'share'])->name('share');
        Route::post('/{gig}/global-message', [GigController::class, 'sendGlobalMessage'])->name('global-message');
    });
});

// Admin
Route::prefix('admin/gig-positions')->name('admin.gig-positions.')->middleware(['auth', 'admin'])->group(...);
```

---

## 🔑 TRADUZIONI

### Stato Attuale
- ❌ **NO file `lang/it/gigs.php`** - NON ESISTE!
- ✅ Chiavi `gigs.*` usate nelle view: **~120 chiavi**
- ⚠️ Chiavi `common.*` usate: **da migrare a `gigs.*`**
- ⚠️ Chiavi `events.*` usate: **verificare se corrette**

### Chiavi Necessarie (organizzate per sezione)
```php
// General
'title', 'create_gig', 'edit_gig', 'my_gigs', 'browse_all', 'apply_gig'

// Fields
'fields.title', 'fields.description', 'fields.requirements', 'fields.compensation'
'fields.deadline', 'fields.category', 'fields.type', 'fields.language', 'fields.location'
'fields.is_remote', 'fields.is_urgent', 'fields.is_featured', 'fields.max_applications'
'fields.event', 'fields.group', 'fields.allow_group_admin_edit'

// Help Texts
'help.title', 'help.description', 'help.requirements', 'help.compensation'
'help.deadline', 'help.location', 'help.max_applications', 'help.is_remote'
'help.is_urgent', 'help.is_featured', 'help.event', 'help.group', 'help.allow_group_admin_edit'

// Placeholders
'placeholders.title', 'placeholders.description', 'placeholders.requirements'
'placeholders.compensation', 'placeholders.location'

// Status
'status.title', 'status.open', 'status.closed', 'status.expired', 'status.urgent', 'status.featured'

// Actions
'actions.apply', 'actions.view', 'actions.view_gig', 'actions.close_gig', 'actions.reopen_gig'
'actions.share', 'actions.send_global_message', 'actions.send_message', 'actions.message'
'actions.message_placeholder', 'actions.read'

// Applications
'applications.title', 'applications.apply', 'applications.submit_application'
'applications.my_applications', 'applications.manage_applications', 'applications.applications_list'
'applications.no_applications', 'applications.no_applications_description'
'applications.total_applications', 'applications.pending_applications', 'applications.accepted_applications'
'applications.pending', 'applications.accepted', 'applications.rejected', 'applications.withdrawn'
'applications.accept', 'applications.reject', 'applications.already_applied'
'applications.application_sent', 'applications.application_withdrawn'
'applications.message', 'applications.message_placeholder'
'applications.experience', 'applications.experience_placeholder'
'applications.portfolio', 'applications.portfolio_placeholder', 'applications.view_portfolio'
'applications.availability', 'applications.availability_placeholder'
'applications.compensation_expectation', 'applications.compensation_expectation_placeholder'
'applications.max_positions'

// Filters
'filters.title', 'filters.search', 'filters.filter_by_category', 'filters.filter_by_type'
'filters.select_category', 'filters.select_type', 'filters.select_language'
'filters.select_event', 'filters.select_group'
'filters.show_featured', 'filters.show_urgent', 'filters.show_remote'
'filters.sort_by', 'filters.sort_options'

// Stats
'stats.total_gigs', 'stats.open_gigs_count', 'stats.urgent_gigs_count'
'stats.my_gigs_count', 'stats.applications', 'stats.total_applications'
'stats.accepted_applications_count'

// Messages
'messages.gig_created', 'messages.gig_updated', 'messages.gig_deleted'
'messages.gig_closed', 'messages.gig_reopened'
'messages.application_accepted', 'messages.application_rejected'
'messages.no_gigs_found', 'messages.no_gigs_description'
'messages.no_my_gigs', 'messages.no_my_gigs_description'
'messages.no_my_applications', 'messages.no_my_applications_description'
'messages.login_to_interact', 'messages.audience_not_allowed'

// Categories & Types
'categories', 'categories.traduzione'
'types', 'languages', 'remote'

// Translation Jobs
'translation_jobs', 'translation_jobs_description'

// Organizer Section
'organizer_section.title', 'organizer_section.gigs', 'organizer_section.add_gig'

// About
'about_author'

// Create
'create.publication_options'
```

---

## 🎨 COMPONENTI LIVEWIRE DA CREARE

### 1. **`GigIndex`** (Public List)
**Path:** `app/Livewire/Gigs/GigIndex.php`
**View:** `resources/views/livewire/gigs/gig-index.blade.php`

**Responsabilità:**
- Lista gigs pubblici con paginazione
- Filtri (categoria, tipo, lingua, location, featured, urgent, remote)
- Search
- Sort
- Statistiche

**Properties:**
```php
public $search = '';
public $category = '';
public $type = '';
public $language = '';
public $location = '';
public $show_featured = false;
public $show_urgent = false;
public $show_remote = false;
public $sort_by = 'created_at';
public $sort_direction = 'desc';
```

**Metodi:**
```php
- updatedSearch()
- updatedCategory()
- clearFilters()
- sortBy($field)
- getGigsProperty()
- getStatsProperty()
```

---

### 2. **`GigShow`** (Single Gig Detail)
**Path:** `app/Livewire/Gigs/GigShow.php`
**View:** `resources/views/livewire/gigs/gig-show.blade.php`

**Responsabilità:**
- Mostra dettagli gig completo
- Form candidatura inline
- Lista candidature (se owner)
- Azioni gig (close, reopen, share, delete)

**Properties:**
```php
public Gig $gig;
public $showApplicationForm = false;
public $applicationMessage = '';
public $applicationExperience = '';
public $applicationPortfolio = '';
```

**Metodi:**
```php
- mount($gigId)
- toggleApplicationForm()
- submitApplication()
- closeGig()
- reopenGig()
- shareGig()
- deleteGig()
```

---

### 3. **`GigCreation`** (Create New Gig)
**Path:** `app/Livewire/Gigs/GigCreation.php`
**View:** `resources/views/livewire/gigs/gig-creation.blade.php`

**Responsabilità:**
- Form creazione gig completo
- Validazione real-time
- Upload immagini (se necessario)

**Properties:**
```php
public $title = '';
public $description = '';
public $requirements = '';
public $compensation = '';
public $deadline = '';
public $category = '';
public $type = '';
public $language = '';
public $location = '';
public $is_remote = false;
public $is_urgent = false;
public $is_featured = false;
public $max_applications = null;
public $event_id = null;
public $group_id = null;
public $allow_group_admin_edit = false;
```

**Metodi:**
```php
- rules()
- save()
- saveAsDraft()
```

---

### 4. **`GigEdit`** (Edit Existing Gig)
**Path:** `app/Livewire/Gigs/GigEdit.php`
**View:** `resources/views/livewire/gigs/gig-edit.blade.php`

**Responsabilità:**
- Form modifica gig
- Caricamento dati esistenti
- Validazione

**Properties:**
```php
public Gig $gig;
// + tutti i campi come in GigCreation
```

**Metodi:**
```php
- mount($gigId)
- loadGigData()
- rules()
- save()
```

---

### 5. **`MyGigs`** (User's Created Gigs)
**Path:** `app/Livewire/Gigs/MyGigs.php`
**View:** `resources/views/livewire/gigs/my-gigs.blade.php`

**Responsabilità:**
- Lista gigs creati dall'utente
- Filtri per status
- Quick actions (edit, delete, close, reopen)
- Statistiche personali

**Properties:**
```php
public $status_filter = 'all'; // all, open, closed, expired
public $sort_by = 'created_at';
```

**Metodi:**
```php
- getMyGigsProperty()
- getStatsProperty()
- closeGig($gigId)
- reopenGig($gigId)
- deleteGig($gigId)
```

---

### 6. **`MyApplications`** (User's Applications)
**Path:** `app/Livewire/Gigs/MyApplications.php`
**View:** `resources/views/livewire/gigs/my-applications.blade.php`

**Responsabilità:**
- Lista candidature inviate dall'utente
- Filtri per status
- Ritira candidatura

**Properties:**
```php
public $status_filter = 'all'; // all, pending, accepted, rejected
```

**Metodi:**
```php
- getMyApplicationsProperty()
- withdrawApplication($applicationId)
```

---

### 7. **`ApplicationsManagement`** (Manage Gig Applications)
**Path:** `app/Livewire/Gigs/ApplicationsManagement.php`
**View:** `resources/views/livewire/gigs/applications-management.blade.php`

**Responsabilità:**
- Lista candidature ricevute per un gig
- Accept/Reject candidature
- Invio messaggi ai candidati
- Filtri per status

**Properties:**
```php
public Gig $gig;
public $status_filter = 'all';
public $message = '';
public $selectedApplicationId = null;
```

**Metodi:**
```php
- mount($gigId)
- getApplicationsProperty()
- acceptApplication($applicationId)
- rejectApplication($applicationId)
- sendMessage($applicationId)
```

---

## 📋 PIANO DI MIGRAZIONE

### FASE 1: Setup & Traduzioni ✅ (DA FARE ORA)
1. ✅ Creare `lang/it/gigs.php` con TUTTE le chiavi necessarie
2. ✅ Sostituire tutte le chiavi `common.*` → `gigs.*` nelle view esistenti
3. ✅ Verificare chiavi `events.*` e rimuovere se inappropriate

### FASE 2: Componenti Base (Index & Show)
1. Creare `app/Livewire/Gigs/GigIndex.php`
2. Creare view `livewire/gigs/gig-index.blade.php`
3. Creare `app/Livewire/Gigs/GigShow.php`
4. Creare view `livewire/gigs/gig-show.blade.php`
5. Aggiornare routes per puntare ai componenti Livewire
6. Testare funzionalità base

### FASE 3: CRUD Components
1. Creare `GigCreation` component
2. Creare `GigEdit` component
3. Aggiornare routes
4. Testare creazione/modifica

### FASE 4: User Gigs & Applications
1. Creare `MyGigs` component
2. Creare `MyApplications` component
3. Creare `ApplicationsManagement` component
4. Aggiornare routes
5. Testare workflow completo candidature

### FASE 5: Pulizia & Ottimizzazione
1. Eliminare controller `GigController.php`
2. Eliminare view blade tradizionali (eccetto master layout)
3. Eliminare file di backup/test
4. Ottimizzare query (eager loading)
5. Testing completo

---

## ⚠️ CONSIDERAZIONI TECNICHE

### Autorizzazioni & Policies
- Implementare `GigPolicy` per can('view'), can('update'), can('delete')
- Verificare ruoli: audience NON può vedere/interagire con gigs
- Owner può modificare/eliminare
- Group admin può modificare se `allow_group_admin_edit = true`

### Performance
- Eager loading: `->with(['user', 'event', 'group', 'applications'])`
- Paginazione: 12-15 gigs per pagina
- Cache per statistiche
- Query optimization per filtri

### Validazioni
- Form validation in tempo reale (Livewire rules)
- Date validation (deadline > now)
- Max applications > 0
- Required fields: title, description, deadline

### Notifiche
- Notifica quando si riceve una candidatura
- Notifica quando candidatura viene accettata/rifiutata
- Notifica per share gig
- Notifica per global message

---

## 📊 COMPLESSITÀ STIMATA

| Componente | Righe Stimate | Complessità | Tempo Stimato |
|-----------|---------------|-------------|---------------|
| GigIndex | ~200 | Media | 2h |
| GigShow | ~250 | Alta | 3h |
| GigCreation | ~180 | Media | 2h |
| GigEdit | ~160 | Bassa | 1h |
| MyGigs | ~150 | Bassa | 1.5h |
| MyApplications | ~120 | Bassa | 1h |
| ApplicationsManagement | ~200 | Alta | 2.5h |
| Traduzioni | - | Media | 1h |
| Testing & Debug | - | - | 3h |
| **TOTALE** | **~1260** | - | **~17h** |

---

## 🎯 PRIORITÀ

### P0 - MUST HAVE (Fase 1)
- ✅ File traduzioni `gigs.php`
- ✅ Rimozione dipendenze `common.*`

### P1 - HIGH (Fase 2-3)
- GigIndex (lista pubblica)
- GigShow (dettaglio)
- GigCreation (creazione)
- GigEdit (modifica)

### P2 - MEDIUM (Fase 4)
- MyGigs
- MyApplications
- ApplicationsManagement

### P3 - LOW (Fase 5)
- Pulizia file obsoleti
- Ottimizzazioni
- Testing avanzato

---

## 📝 NOTE FINALI

1. **Eliminare File Obsoleti DOPO migrazione:**
   - `index-original.blade.php`
   - `index-backup.blade.php`
   - `index-simple.blade.php`
   - `test.blade.php`

2. **Mantenere per Riferimento (temporaneo):**
   - `create.blade.php`
   - `edit.blade.php`
   - `show.blade.php`
   - (eliminare dopo verifica Livewire)

3. **Controller da Eliminare:**
   - `GigController.php` (dopo migrazione completa)

4. **Admin:**
   - `GigPositionController` può rimanere come controller tradizionale (uso admin raro)
   - Oppure migrare successivamente

---

## ✅ PROSSIMI PASSI IMMEDIATI

1. **ADESSO:** Creare `lang/it/gigs.php` con tutte le 120+ chiavi
2. **ADESSO:** Sostituire `common.*` → `gigs.*` in tutte le view
3. **POI:** Iniziare Fase 2 con GigIndex e GigShow

