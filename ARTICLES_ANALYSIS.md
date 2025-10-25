# 📊 ANALISI COMPLETA SEZIONE ARTICLES

## 🎯 STATO ATTUALE
**Sistema Legacy**: Controller-based (NON Livewire)

---

## 📁 STRUTTURA

### Controllers (7 files, 2,161 lines total)
1. **ArticleController.php** (918 lines, 18 methods)
   - index, create, store, show, edit, update, destroy
   - search, publish, unpublish, toggleFeatured, feature, unfeature
   - myArticles, browse, browseByCategory, browseByTag

2. **Admin/ArticleController.php** (324 lines, 12 methods)
   - Admin CRUD + moderation

3. **ArticleLayoutController.php** (236 lines, 8 methods)
   - Gestione layout editor-controlled

4. **ArticleReportController.php** (263 lines, 11 methods)
   - Sistema di segnalazioni

5. **ArticleCategoryController.php** (64 lines, 7 methods)
   - Gestione categorie

6. **ArticleTagController.php** (64 lines, 7 methods)
   - Gestione tag

7. **Admin/ArticleTranslationController.php** (292 lines, 11 methods)
   - Sistema traduzioni multilingua

### Models (6 files, 962 lines total)
1. **Article.php** (443 lines)
   - Traits: HasLikes, HasComments, HasViews, HasModeration, Reportable
   - Multilingua: title, content, excerpt (array casts)
   - Relazioni: user, moderator, category, tags, reports, translations
   
2. **ArticleCategory.php** (120 lines)
3. **ArticleTag.php** (108 lines)
4. **ArticleLayout.php** (103 lines) - Sistema layout editoriale
5. **ArticleTranslation.php** (78 lines)
6. **ArticleReport.php** (110 lines)

### Views (13 files, 2,734+ lines)
**Public Views:**
- `index.blade.php` (894 lines) - Lista articoli con layout editor-controlled
- `show.blade.php` (543 lines) - Dettaglio articolo
- `create.blade.php` (348 lines) - Creazione articolo
- `edit.blade.php` (434 lines) - Modifica articolo
- `my-articles.blade.php` (407 lines) - I miei articoli
- `search.blade.php` (108 lines) - Ricerca articoli

**Partials:**
- `partials/article-card.blade.php`
- `partials/article-horizontal.blade.php`
- `partials/articles-list.blade.php`
- `partials/sidebar-article.blade.php`

**Layout System:**
- `layout/index.blade.php` - Admin layout management
- `layout/article-preview.blade.php`
- `layout/preview.blade.php`

**Admin Views:** (non contate sopra)
- Admin CRUD completo
- Translation management
- Moderation system

### Database (11 migrations)
**Tabelle principali:**
- `articles` - Contenuto articoli (multilingua)
- `article_categories` - Categorie
- `article_tags` - Tag
- `article_tag` - Pivot table
- `article_layouts` - Sistema layout editoriale
- `article_translations` - Traduzioni articoli
- `article_likes` - Sistema mi piace
- `article_comments` - Commenti
- `article_reports` - Segnalazioni

**Colonne aggiuntive:**
- Moderazione (status, notes, moderated_by, moderated_at)
- SEO (meta_title, meta_description, meta_keywords, slug)
- Pubblicazione (published_at, status, featured)
- Traduzioni (language, original_language, needs_translation, translation_status)
- News flag (is_news)

---

## 🎨 FEATURES PRINCIPALI

### 1. Sistema Editoriale Avanzato
- **Layout Editor-Controlled**: Gli editor possono gestire layout homepage
- **Posizioni Layout**:
  - Banner (1 articolo principale in alto)
  - Featured (articoli in evidenza)
  - Latest (ultimi articoli)
  - Popular (più popolari)
- **Toggle Show All**: Mostra tutti gli articoli vs layout controllato

### 2. Sistema Multilingua Completo
- Articoli con traduzioni multiple
- Campi multilingua: title, content, excerpt, meta
- Translation management dedicato
- Traduzione automatica/manuale
- Status traduzioni tracciato

### 3. Sistema Social
- **Likes**: Con trait HasLikes
- **Comments**: Sistema commenti completo
- **Views**: Contatore visualizzazioni
- **Shares**: Sistema condivisione

### 4. Moderazione & Report
- Sistema moderazione articoli
- Segnalazioni utenti
- Approvazione/Rifiuto
- Note moderazione

### 5. Categorizzazione & Tagging
- Categorie gerarchiche
- Tag multipli per articolo
- Filtri per categoria/tag
- Browse by category/tag

### 6. SEO & Publishing
- Slug personalizzabili
- Meta tag completi
- Featured articles
- Pubblicazione programmata
- Draft/Published status

### 7. Ricerca Avanzata
- Ricerca full-text (title, excerpt, content)
- Filtri: categoria, tag, featured
- Ordinamenti: newest, oldest, popular, title

### 8. Sistema Permessi
- `articles.create` - Creare articoli
- `articles.manage_news` - Gestire news
- `articles.moderate` - Moderare
- Admin-only features

---

## 📊 COMPLESSITÀ

### Metriche
- **Controllers**: 7 files, ~2,200 lines
- **Models**: 6 files, ~1,000 lines
- **Views**: 13+ files, ~3,000+ lines (public + admin)
- **Migrations**: 11 tabelle
- **Routes**: ~30 routes
- **Metodi pubblici**: 74+ methods

### Dipendenze
- Sistema likes (trait)
- Sistema comments (trait)
- Sistema views (trait)
- Sistema moderazione (trait)
- Sistema segnalazioni (trait)
- Translation system custom
- Editor layout system
- Permission system

---

## 🚀 STRATEGIA REFACTOR CONSIGLIATA

### Opzione 1: REFACTOR COMPLETO (Tempo: 8-12 ore)
**Pros:**
- Sistema moderno Livewire 3
- Eliminazione JS custom
- Mobile-first ottimizzato
- Consistenza con resto app

**Cons:**
- Tempo richiesto elevato
- Rischio breaking changes
- Molte features da reimplementare

**Componenti Livewire necessari:**
1. `ArticleIndex` - Lista articoli + layout editor
2. `ArticleShow` - Dettaglio + comments/likes
3. `ArticleCreation` - Form creazione multi-step
4. `ArticleEdit` - Form modifica
5. `MyArticles` - I miei articoli
6. `ArticleSearch` - Ricerca avanzata
7. `ArticleCard` - Card riutilizzabile
8. `ArticleSidebar` - Filtri sidebar
9. `LayoutManager` - Admin layout (Admin/ArticleLayoutManager)
10. `TranslationManager` - Traduzioni (Admin/ArticleTranslationManager)

### Opzione 2: SOLO TRADUZIONI (Tempo: 30 min)
**Pros:**
- Veloce
- Zero breaking changes
- Sistema funzionante mantenu to

**Cons:**
- Lascia codice legacy
- Non allineato con resto app Livewire

**Steps:**
1. Estrai tutte le chiavi `__('articles.*)`
2. Crea `lang/it/articles.php`
3. Traduci ~150-200 chiavi
4. Test

### Opzione 3: REFACTOR PROGRESSIVO (Tempo: 2-3 ore)
**Pros:**
- Refactor graduale
- Test incrementale
- Meno rischio

**Cons:**
- Sistema misto temporaneamente

**Priorità:**
1. ✅ ArticleIndex (più usata)
2. ✅ ArticleShow (dettaglio)
3. ✅ ArticleCreation/Edit (forms)
4. ⏭️ MyArticles
5. ⏭️ Admin (dopo)

---

## 🎯 RACCOMANDAZIONE

**OPZIONE 3: REFACTOR PROGRESSIVO**

Iniziare con le 3 pagine pubbliche principali:
1. Index (lista + layout)
2. Show (dettaglio)
3. Create/Edit (forms)

Lasciare per dopo:
- Admin management
- Translation system (già funzionante)
- Layout editor (feature admin)

**Tempo stimato**: 3-4 ore
**Rischio**: Medio-Basso
**Beneficio**: Alto (pagine pubbliche moderne)

---

## 📝 CHIAVI TRADUZIONE TROVATE

Dalla scan preliminare, circa **150-200 chiavi** da tradurre in `articles.*`

Sezioni principali:
- General (articles, create_article, editor_picks, ecc.)
- Categories & Tags
- Filters & Search  
- Status & Actions (publish, unpublish, feature, ecc.)
- Comments & Social
- Forms (title, content, excerpt, ecc.)
- Validation messages
- Admin labels

---

## ⚠️ NOTES

1. **Sistema Layout**: Unico, potrebbe essere riutilizzabile per altre sezioni
2. **Multilingua**: Sistema custom, valutare se mantenere o semplificare
3. **Moderazione**: Sistema complesso, testare accuratamente
4. **SEO**: Meta tag importanti, non perdere funzionalità
5. **Permissions**: Verificare tutte le permission gates

---

**Data Analisi**: {{ date }}
**Analizzato da**: AI Assistant
**Status**: ✅ Completo

