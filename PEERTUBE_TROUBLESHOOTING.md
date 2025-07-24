# 🔧 Troubleshooting PeerTube Authentication

## 📋 Problema Identificato

**Errore in produzione:**
```
PeerTube authentication error: Impossibile ottenere access token: {"type":"https://docs.joinpeertube.org/api-rest-reference.html#section/Errors/invalid_grant","detail":"Invalid grant: user credentials are invalid","status":400,"docs":"https://docs.joinpeertube.org/api-rest-reference.html#operation/getOAuthToken","code":"invalid_grant"}
```

**Stato attuale:**
- ✅ **Locale**: Autenticazione PeerTube funziona perfettamente
- ❌ **Produzione**: Autenticazione fallisce con credenziali invalide

## 🔍 Analisi del Problema

### 1. Configurazione Attuale (Locale)
```
URL: https://video.slamin.it
Username: admin
Password: [configurata]
Status: ✅ FUNZIONANTE
```

### 2. Possibili Cause in Produzione

#### A. Credenziali Diverse
- **Username diverso**: In produzione potrebbe essere configurato un username diverso da "admin"
- **Password diversa**: La password admin PeerTube potrebbe essere cambiata
- **Account non admin**: L'utente potrebbe non avere i permessi admin

#### B. Configurazione OAuth
- **OAuth disabilitato**: L'autenticazione OAuth potrebbe essere disabilitata in produzione
- **Client OAuth diverso**: Il client OAuth potrebbe essere configurato diversamente
- **Restrizioni IP**: Potrebbero esserci restrizioni IP per l'autenticazione

#### C. Problemi di Rete/Firewall
- **Firewall**: Blocca le richieste OAuth
- **Proxy**: Interferisce con le richieste di autenticazione
- **SSL/TLS**: Problemi di certificati SSL

## 🛠️ Soluzioni

### 1. Verifica Configurazione Produzione

#### A. Accedi alla Pagina di Configurazione
```
URL: https://tuodominio.com/admin/peertube/config
```

#### B. Verifica Credenziali
1. Controlla che l'URL sia corretto: `https://video.slamin.it`
2. Verifica username admin PeerTube
3. Reinserisci la password admin PeerTube
4. Salva le configurazioni

#### C. Test di Connessione
1. Clicca "Test Connessione" per verificare l'URL
2. Clicca "Test Autenticazione" per verificare le credenziali
3. Se fallisce, controlla i log per dettagli

### 2. Comandi di Diagnosi

#### A. Test Generale
```bash
php artisan peertube:test-page
```

#### B. Diagnosi Dettagliata
```bash
php artisan peertube:diagnose-auth --detailed
```

#### C. Confronto Ambienti
```bash
php artisan peertube:compare-environments --prod-url=https://video.slamin.it
```

#### D. Correzione Configurazione
```bash
php artisan peertube:fix-config --force
```

### 3. Verifica Manuale PeerTube

#### A. Login Manuale
1. Vai su `https://video.slamin.it`
2. Fai login con le credenziali admin
3. Verifica che l'utente abbia i permessi admin

#### B. Verifica OAuth
1. Vai su `https://video.slamin.it/api/v1/oauth-clients/local`
2. Verifica che restituisca un client OAuth valido
3. Controlla che non ci siano restrizioni

#### C. Test API Diretto
```bash
curl -X POST https://video.slamin.it/api/v1/users/token \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "client_id=CLIENT_ID&client_secret=CLIENT_SECRET&grant_type=password&username=admin&password=PASSWORD"
```

### 4. Reset Configurazione

Se necessario, resetta completamente la configurazione:

```bash
php artisan peertube:fix-config --reset
```

Poi riconfigura manualmente tramite l'interfaccia admin.

## 📊 Checklist Risoluzione

- [ ] Accedere alla pagina di configurazione PeerTube
- [ ] Verificare URL PeerTube: `https://video.slamin.it`
- [ ] Verificare username admin PeerTube
- [ ] Reinserire password admin PeerTube
- [ ] Eseguire test di connessione
- [ ] Eseguire test di autenticazione
- [ ] Verificare login manuale su PeerTube
- [ ] Controllare permessi admin utente
- [ ] Verificare configurazione OAuth
- [ ] Testare creazione utente PeerTube

## 🔗 Link Utili

- **Configurazione PeerTube**: `/admin/peertube/config`
- **Impostazioni Generali**: `/admin/settings`
- **Documentazione PeerTube API**: https://docs.joinpeertube.org/api-rest-reference.html
- **PeerTube Instance**: https://video.slamin.it

## 📝 Note Importanti

1. **Credenziali Sicure**: Le password sono criptate nel database
2. **Token Automatici**: I token di accesso sono gestiti automaticamente
3. **Fallback**: Se PeerTube non funziona, la registrazione utenti continua normalmente
4. **Log**: Controlla i log Laravel per errori dettagliati

## 🚨 Se il Problema Persiste

1. **Contatta l'amministratore PeerTube** per verificare le credenziali
2. **Controlla i log PeerTube** per errori lato server
3. **Verifica la versione PeerTube** e aggiorna se necessario
4. **Considera un reset completo** della configurazione OAuth

---

**Ultimo aggiornamento**: 2025-07-24
**Stato**: Configurazione locale funzionante, problema identificato in produzione 