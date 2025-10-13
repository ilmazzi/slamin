# 🚀 Guida alla Migrazione Livewire

## 📋 Panoramica

Questa guida documenta la migrazione da JavaScript vanilla a Livewire 3 per l'applicazione Slam In.

## ✅ Componenti Completati

### 1. Sistema Chat (`App\Livewire\Chat\ChatRoom`)

**Sostituisce**: `resources/js/chat-realtime.js` (387 righe)

**Funzionalità**:
- Messaggi real-time con Echo/Pusher
- Indicatori di digitazione
- Presenza utenti online
- Scroll automatico
- Notifiche

**Utilizzo**:
```blade
<livewire:chat.chat-room :roomId="$roomId" />
```

**Rotta**:
```php
Route::get('/chat/room/{roomId}', function ($roomId) {
    return view('chat.livewire-room', compact('roomId'));
})->name('chat.room');
```

---

### 2. Emoji Picker (`App\Livewire\Chat\EmojiPicker`)

**Sostituisce**: `resources/js/emoji-picker.js` (147 righe)

**Funzionalità**:
- 340+ emoji organizzate in 8 categorie
- Ricerca emoji
- Inserimento automatico nel campo di input
- Design responsive

**Utilizzo**:
```blade
<livewire:chat.emoji-picker />
```

**Eventi Livewire**:
```javascript
// Ascolta la selezione di un emoji
Livewire.on('emoji-selected', (emoji) => {
    // Inserisci emoji nell'input
});
```

---

### 3. Ricerca Chat (`App\Livewire\Chat\ChatSearch`)

**Sostituisce**: Funzioni JavaScript per ricerca utenti

**Funzionalità**:
- Ricerca utenti in tempo reale
- Autocompletamento
- Creazione automatica chat private
- Gestione chat recenti

**Utilizzo**:
```blade
<livewire:chat.chat-search />
```

---

### 4. Quill Editor (`App\Livewire\Components\QuillEditor`)

**Sostituisce**: `resources/js/quill-editor.js` (67 righe)

**Funzionalità**:
- Editor WYSIWYG completo
- Toolbar configurabile (basic, full, poetry, minimal)
- Sincronizzazione automatica con Livewire
- Validazione integrata

**Utilizzo**:
```blade
<livewire:components.quill-editor 
    wire:model="content"
    placeholder="Scrivi qui..."
    height="300px"
    toolbar="poetry" />
```

**Opzioni Toolbar**:
- `basic`: Bold, Italic, Underline, Link, Clean
- `full`: Tutti gli strumenti disponibili
- `poetry`: Ottimizzato per poesie (Bold, Italic, Align, Blockquote)
- `minimal`: Solo Bold e Italic

---

### 5. Creazione Poesia (`App\Livewire\Poems\PoemCreate`)

**Sostituisce**: Form tradizionale con JavaScript

**Funzionalità**:
- Validazione real-time
- Upload immagini con preview
- Salvataggio bozze
- Gestione tag e categorie
- Integrazione Quill Editor

**Utilizzo**:
```php
Route::get('/poems/create-livewire', function () {
    return view('poems.create-livewire');
})->name('poems.create.livewire');
```

**Nella vista**:
```blade
<livewire:poems.poem-create />
```

---

## 🔧 Configurazione

### Requisiti

1. **Livewire 3** installato
2. **Alpine.js** per interazioni client-side
3. **Echo** per broadcasting (opzionale per chat)
4. **Quill.js** per l'editor WYSIWYG

### Setup Broadcasting (per Chat)

**`.env`**:
```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=eu
```

**`config/broadcasting.php`**: Già configurato

**`resources/js/bootstrap.js`**: Echo già inizializzato

---

## 📝 Pattern di Utilizzo

### 1. Componente Base

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class MyComponent extends Component
{
    public $property = '';
    
    protected $rules = [
        'property' => 'required|string'
    ];
    
    public function save()
    {
        $this->validate();
        // Logica di salvataggio
    }
    
    public function render()
    {
        return view('livewire.my-component');
    }
}
```

### 2. Vista Livewire

```blade
<div>
    <input type="text" wire:model="property" class="form-control">
    @error('property') 
        <span class="text-danger">{{ $message }}</span> 
    @enderror
    
    <button wire:click="save" wire:loading.attr="disabled">
        <span wire:loading.remove>Salva</span>
        <span wire:loading>Salvando...</span>
    </button>
</div>
```

### 3. Integrazione Alpine.js

```blade
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>
    
    <div x-show="open" x-transition>
        <livewire:my-component />
    </div>
</div>
```

---

## 🎯 Best Practices

### 1. **Usa `wire:model.live` per aggiornamenti real-time**
```blade
<input wire:model.live="search">
```

### 2. **Gestisci lo stato di caricamento**
```blade
<button wire:click="save" wire:loading.attr="disabled">
    <span wire:loading.remove>Salva</span>
    <span wire:loading>
        <div class="spinner-border spinner-border-sm"></div>
    </span>
</button>
```

### 3. **Valida sempre i dati**
```php
protected $rules = [
    'email' => 'required|email',
    'password' => 'required|min:8'
];

public function save()
{
    $this->validate();
    // ...
}
```

### 4. **Usa eventi per comunicazione tra componenti**
```php
// Emetti evento
$this->dispatch('user-updated', userId: $user->id);

// Ascolta evento
protected $listeners = ['user-updated' => 'handleUserUpdate'];
```

### 5. **Ottimizza le query**
```php
public function render()
{
    return view('livewire.component', [
        'items' => Item::where('active', true)->get()
    ]);
}
```

---

## 🚀 Prossimi Passi

### Fase 4: Event Handlers Inline
- [ ] Sostituire `onclick` con `wire:click`
- [ ] Sostituire `onchange` con `wire:change`
- [ ] Sostituire `oninput` con `wire:input`
- [ ] Sostituire `onsubmit` con `wire:submit.prevent`

### Fase 5: Pulizia Finale
- [ ] Rimuovere `chat-realtime.js`
- [ ] Rimuovere `emoji-picker.js`
- [ ] Rimuovere `quill-editor.js`
- [ ] Aggiornare `app.js` per rimuovere import non necessari
- [ ] Test completo di tutte le funzionalità

---

## 📊 Statistiche Migrazione

| Componente | JavaScript Rimosso | Livewire Creato | Stato |
|------------|-------------------|-----------------|-------|
| Chat System | 387 righe | ChatRoom.php | ✅ |
| Emoji Picker | 147 righe | EmojiPicker.php | ✅ |
| Quill Editor | 67 righe | QuillEditor.php | ✅ |
| Chat Search | N/A | ChatSearch.php | ✅ |
| Poem Create | N/A | PoemCreate.php | ✅ |
| **TOTALE** | **601 righe** | **5 componenti** | **✅** |

---

## 🐛 Troubleshooting

### Problema: "Livewire component not found"
**Soluzione**: Verifica che il namespace sia corretto e che il componente sia registrato.

### Problema: "Property not found"
**Soluzione**: Assicurati che la proprietà sia dichiarata come `public` nel componente.

### Problema: "Echo not defined"
**Soluzione**: Verifica che Echo sia inizializzato in `bootstrap.js` e che Pusher sia configurato.

### Problema: "Quill is not defined"
**Soluzione**: Assicurati che Quill.js sia caricato prima del componente Livewire.

---

## 📚 Risorse

- [Livewire Documentation](https://livewire.laravel.com/docs)
- [Alpine.js Documentation](https://alpinejs.dev)
- [Laravel Broadcasting](https://laravel.com/docs/broadcasting)
- [Quill.js Documentation](https://quilljs.com/docs)

---

## 👥 Contributori

- **Migrazione Livewire**: Completata in 3 fasi
- **Data**: Ottobre 2025
- **Branch**: `feature/livewire-complete-migration`

---

## 📝 Note

Questa migrazione migliora significativamente:
- ✅ **Manutenibilità**: Codice più organizzato e testabile
- ✅ **Performance**: Meno JavaScript client-side
- ✅ **Developer Experience**: Sintassi Laravel familiare
- ✅ **Scalabilità**: Componenti riutilizzabili
- ✅ **SEO**: Rendering server-side

---

**Ultima modifica**: Ottobre 2025
**Versione**: 1.0.0
**Stato**: In Produzione (Fase 1-3 Completate)

