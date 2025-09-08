# Test Autorizzazioni Searchbar Globale

## Scenari di Test

### 1. Utente Non Autenticato
- ✅ **Dovrebbe vedere**: Solo contenuti pubblici e approvati
- ❌ **NON dovrebbe vedere**: 
  - Contenuti privati
  - Contenuti in moderazione
  - Bozze
  - Contenuti di altri utenti

### 2. Utente Autenticato Normale
- ✅ **Dovrebbe vedere**: 
  - Contenuti pubblici e approvati
  - Propri contenuti (anche privati e bozze)
  - Eventi di cui è organizzatore/venue owner
- ❌ **NON dovrebbe vedere**: 
  - Contenuti privati di altri utenti
  - Contenuti in moderazione di altri utenti

### 3. Utente Moderatore
- ✅ **Dovrebbe vedere**: 
  - Tutti i contenuti pubblici
  - Contenuti in moderazione
  - Propri contenuti
- ❌ **NON dovrebbe vedere**: 
  - Contenuti privati di altri utenti (a meno che non sia necessario per moderazione)

### 4. Utente Admin
- ✅ **Dovrebbe vedere**: 
  - TUTTI i contenuti
  - Contenuti privati
  - Contenuti in moderazione
  - Bozze di tutti gli utenti

## Controlli Implementati

### Poesie
- **Pubblico**: Solo `is_public = true` e `moderation_status = 'approved'`
- **Autenticato**: Pubbliche + proprie (anche private/bozze)
- **Moderatore/Admin**: Tutte

### Eventi
- **Pubblico**: Solo `is_public = true`
- **Autenticato**: Pubblici + propri (come organizzatore/venue owner)
- **Moderatore/Admin**: Tutti

### Video
- **Pubblico**: Solo `is_public = true`
- **Autenticato**: Pubblici + propri
- **Moderatore/Admin**: Tutti

### Gig
- **Pubblico**: Solo `is_closed = false`
- **Autenticato**: Aperti + propri (anche chiusi)
- **Moderatore/Admin**: Tutti

### Utenti
- **Pubblico**: Non accessibile
- **Autenticato**: Solo profili pubblici (`is_public = true`)
- **Moderatore/Admin**: Tutti i profili

## Test da Eseguire

1. **Test senza login**: Verificare che appaiano solo contenuti pubblici
2. **Test con login normale**: Verificare che appaiano contenuti pubblici + propri
3. **Test con login moderatore**: Verificare che appaiano contenuti in moderazione
4. **Test con login admin**: Verificare che appaiano tutti i contenuti
5. **Test contenuti privati**: Verificare che non appaiano per altri utenti
6. **Test contenuti in moderazione**: Verificare che non appaiano per utenti normali

## Note di Sicurezza

- ✅ Controlli di autorizzazione a livello di query
- ✅ Filtri basati su ruoli utente
- ✅ Controlli di moderazione
- ✅ Esclusione contenuti privati per utenti non autorizzati
- ✅ Protezione dati sensibili

## Prossimi Passi

1. Testare tutti gli scenari
2. Verificare performance con grandi dataset
3. Aggiungere logging per audit trail
4. Implementare cache per query frequenti
5. Aggiungere metriche di ricerca


