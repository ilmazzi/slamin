# 🌍 Comandi per Traduzione API

## Comandi da Terminale

### 1. Lista Provider Disponibili
```bash
php artisan translations:list-providers
```
Mostra tutti i provider di traduzione disponibili con i loro dettagli e testa le connessioni.

### 2. Configurazione API
```bash
php artisan translations:configure-api --provider=google --api-key=YOUR_KEY
php artisan translations:configure-api --provider=libre --test
```
Configura un provider di traduzione o testa la connessione.

### 3. Traduzione di una Pagina Specifica
```bash
php artisan translations:translate-page en admin --provider=libre --force
php artisan translations:translate-page es common --provider=google --api-key=YOUR_KEY
php artisan translations:translate-page fr auth --provider=deepl --api-key=YOUR_KEY --force
```

**Parametri:**
- `language`: Codice lingua target (en, es, fr, de, pt, etc.)
- `file`: Nome del file di traduzione (admin, common, auth, dashboard, etc.)
- `--provider`: Provider da usare (libre, google, deepl, microsoft)
- `--api-key`: Chiave API (opzionale per LibreTranslate)
- `--force`: Forza la traduzione anche se già tradotto

### 4. Traduzione Completa di Tutte le Lingue
```bash
php artisan translations:api-translate en --provider=libre --force
php artisan translations:api-translate es --provider=google --api-key=YOUR_KEY
```

## Provider Disponibili

### 1. LibreTranslate (Gratuito)
- **URL**: https://libretranslate.com/translate
- **Chiave API**: Non richiesta
- **Limiti**: 1000 richieste/ora (tier gratuito)
- **Costo**: Gratuito

### 2. Google Translate
- **URL**: https://translation.googleapis.com/language/translate/v2
- **Chiave API**: Richiesta
- **Limiti**: 1M richieste/giorno
- **Costo**: $20/1M caratteri

### 3. DeepL
- **URL**: https://api-free.deepl.com/v2/translate
- **Chiave API**: Richiesta
- **Limiti**: 500K richieste/mese (tier gratuito)
- **Costo**: $25/1M caratteri

### 4. Microsoft Translator
- **URL**: https://api.cognitive.microsofttranslator.com/translate
- **Chiave API**: Richiesta
- **Limiti**: 2M richieste/mese (tier gratuito)
- **Costo**: $10/1M caratteri

## Interfaccia Web

### Accesso
Vai a `/admin/translations` e usa la sezione "Traduzione Automatica".

### Funzionalità
1. **Selezione Lingua**: Scegli la lingua target
2. **Selezione File**: Scegli il file di traduzione da tradurre
3. **Selezione Provider**: Scegli il servizio di traduzione
4. **Chiave API**: Inserisci la chiave API (opzionale per LibreTranslate)
5. **Test Connessione**: Testa la connessione al provider
6. **Traduci Pagina**: Avvia la traduzione

### Esempi di Utilizzo

#### Traduzione con LibreTranslate (Gratuito)
1. Seleziona lingua: `English`
2. Seleziona file: `admin`
3. Seleziona provider: `LibreTranslate`
4. Lascia vuoto il campo API Key
5. Clicca "Test Connessione"
6. Clicca "Traduci Pagina"

#### Traduzione con Google Translate
1. Seleziona lingua: `Español`
2. Seleziona file: `common`
3. Seleziona provider: `Google Translate`
4. Inserisci la tua API Key di Google
5. Clicca "Test Connessione"
6. Clicca "Traduci Pagina"

## File di Traduzione Disponibili

- `admin` - Pannello amministrazione
- `common` - Testi comuni
- `auth` - Autenticazione
- `dashboard` - Dashboard utente
- `events` - Eventi
- `poems` - Poesie
- `videos` - Video
- `articles` - Articoli

## Lingue Supportate

- `en` - English
- `es` - Español
- `fr` - Français
- `de` - Deutsch
- `pt` - Português
- `it` - Italiano (riferimento)

## Note Importanti

1. **LibreTranslate** è gratuito ma ha limiti di rate
2. **Google Translate** richiede una API Key valida
3. **DeepL** offre traduzioni di alta qualità
4. **Microsoft Translator** ha un tier gratuito generoso
5. Usa `--force` per sovrascrivere traduzioni esistenti
6. Le traduzioni vengono salvate automaticamente nei file PHP

## Risoluzione Problemi

### Errore "API connection failed"
- Verifica che la chiave API sia corretta
- Controlla la connessione internet
- Per LibreTranslate, verifica che il servizio sia disponibile

### Errore "File not found"
- Verifica che il file di traduzione esista in `lang/it/`
- Controlla che il nome del file sia corretto

### Traduzioni di bassa qualità
- Prova un provider diverso
- Usa DeepL per traduzioni più accurate
- Verifica manualmente le traduzioni critiche
