# 🔍 Analisi Problema PeerTube in Produzione

## 📋 Situazione Attuale

**Problema**: Autenticazione PeerTube fallisce in produzione ma funziona in locale
- ✅ **Locale**: Autenticazione OAuth funziona perfettamente
- ❌ **Produzione**: Errore `invalid_grant: user credentials are invalid`

## 🔍 Diagnosi Effettuata

### 1. Test Locali (Tutti Superati)
- ✅ Connessione HTTP: OK
- ✅ SSL/TLS: Certificato valido fino al 2025-10-20
- ✅ DNS: Risoluzione corretta (157.180.17.34)
- ✅ Timeout: Tutti i livelli funzionano
- ✅ OAuth: Form data standard funziona
- ✅ Endpoint: `/api/v1/users/token` e `/oauth/token` funzionano

### 2. Scoperte Importanti
- ❌ **JSON non supportato**: PeerTube richiede `application/x-www-form-urlencoded`
- ❌ **Headers personalizzati**: Status 406 con alcuni headers
- ✅ **Form data**: Unico formato supportato per OAuth

## 🎯 Possibili Cause del Problema in Produzione

### 1. **Problemi di Configurazione PHP**
```
- Estensioni PHP diverse tra locale e produzione
- Configurazione cURL diversa
- Timeout PHP diversi
- Configurazione SSL/TLS diversa
```

### 2. **Problemi di Rete/Firewall**
```
- Firewall che blocca richieste POST
- Proxy che modifica headers
- Load balancer che interferisce
- Restrizioni IP per l'autenticazione
```

### 3. **Problemi di Configurazione Server**
```
- Configurazione Apache/Nginx diversa
- Headers HTTP modificati dal server
- Compressione che interferisce
- Cache che modifica le richieste
```

### 4. **Problemi di Ambiente**
```
- Versione PHP diversa
- Estensioni mancanti
- Configurazione SSL diversa
- Timeout di sistema diversi
```

## 🛠️ Soluzioni da Provare

### 1. **Test Immediati in Produzione**

#### A. Comando di Diagnosi Completa
```bash
# In produzione, esegui:
php artisan peertube:test-network
php artisan peertube:test-oauth --detailed
php artisan peertube:diagnose-auth --detailed
```

#### B. Test Manuale con curl
```bash
# Test connessione base
curl -I https://video.slamin.it/api/v1/config

# Test OAuth client
curl https://video.slamin.it/api/v1/oauth-clients/local

# Test autenticazione (sostituisci con credenziali reali)
curl -X POST https://video.slamin.it/api/v1/users/token \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "client_id=CLIENT_ID&client_secret=CLIENT_SECRET&grant_type=password&username=admin&password=PASSWORD"
```

### 2. **Verifica Configurazione PHP**

#### A. Confronta configurazioni
```bash
# In locale e produzione, esegui:
php -m | grep -E "(curl|openssl|json|mbstring)"
php --ini
php -i | grep -E "(curl|openssl|timeout)"
```

#### B. Test estensioni specifiche
```php
// Crea un file di test
<?php
echo "cURL: " . (extension_loaded('curl') ? 'OK' : 'MISSING') . "\n";
echo "OpenSSL: " . (extension_loaded('openssl') ? 'OK' : 'MISSING') . "\n";
echo "JSON: " . (extension_loaded('json') ? 'OK' : 'MISSING') . "\n";
echo "cURL version: " . curl_version()['version'] . "\n";
echo "OpenSSL version: " . OPENSSL_VERSION_TEXT . "\n";
```

### 3. **Modifiche al Codice**

#### A. Aumenta timeout e retry
```php
// In PeerTubeService.php, modifica il timeout
private $timeout = 60; // Aumenta da 30 a 60 secondi

// Aggiungi retry logic
private function authenticateWithRetry(): string
{
    $maxRetries = 3;
    $lastException = null;
    
    for ($i = 0; $i < $maxRetries; $i++) {
        try {
            return $this->authenticate();
        } catch (\Exception $e) {
            $lastException = $e;
            if ($i < $maxRetries - 1) {
                sleep(2); // Attendi 2 secondi prima del retry
            }
        }
    }
    
    throw $lastException;
}
```

#### B. Aggiungi logging dettagliato
```php
// In PeerTubeService.php, aggiungi logging
private function authenticate(): string
{
    try {
        Log::info('PeerTube: Iniziando autenticazione', [
            'url' => $this->baseUrl,
            'username' => $this->username,
            'timeout' => $this->timeout
        ]);
        
        // ... resto del codice ...
        
        Log::info('PeerTube: Autenticazione riuscita', [
            'token_length' => strlen($tokenData['access_token'])
        ]);
        
        return $tokenData['access_token'];
        
    } catch (\Exception $e) {
        Log::error('PeerTube: Errore autenticazione', [
            'error' => $e->getMessage(),
            'url' => $this->baseUrl,
            'username' => $this->username
        ]);
        throw $e;
    }
}
```

### 4. **Configurazione Server**

#### A. Verifica headers HTTP
```bash
# Controlla se il server modifica headers
curl -v https://video.slamin.it/api/v1/config
```

#### B. Verifica configurazione SSL
```bash
# Test certificato SSL
openssl s_client -connect video.slamin.it:443 -servername video.slamin.it
```

### 5. **Workaround Temporaneo**

#### A. Disabilita temporaneamente PeerTube
```php
// In AuthController.php, commenta temporaneamente
// $this->createPeerTubeAccount($user, $password);
```

#### B. Usa fallback per autenticazione
```php
// In PeerTubeService.php
public function testAuthentication(): bool
{
    try {
        $this->authenticate();
        return true;
    } catch (\Exception $e) {
        Log::warning('PeerTube: Autenticazione fallita, continuando senza PeerTube', [
            'error' => $e->getMessage()
        ]);
        return false; // Non bloccare l'applicazione
    }
}
```

## 📊 Checklist Risoluzione

### Fase 1: Diagnosi
- [ ] Eseguire comandi di test in produzione
- [ ] Confrontare configurazioni PHP locale vs produzione
- [ ] Testare con curl manuale
- [ ] Verificare log del server web

### Fase 2: Configurazione
- [ ] Aumentare timeout PHP
- [ ] Verificare estensioni PHP
- [ ] Controllare configurazione SSL
- [ ] Verificare headers HTTP

### Fase 3: Codice
- [ ] Aggiungere retry logic
- [ ] Migliorare logging
- [ ] Implementare fallback
- [ ] Testare modifiche

### Fase 4: Verifica
- [ ] Testare autenticazione
- [ ] Verificare creazione utenti
- [ ] Controllare log per errori
- [ ] Monitorare performance

## 🚨 Priorità di Intervento

1. **ALTA**: Eseguire test di diagnosi in produzione
2. **ALTA**: Verificare configurazione PHP
3. **MEDIA**: Implementare retry logic
4. **MEDIA**: Aggiungere logging dettagliato
5. **BASSA**: Modificare configurazione server

## 📝 Note Importanti

- **Non bloccare l'applicazione**: Se PeerTube fallisce, l'app deve continuare a funzionare
- **Logging dettagliato**: Essenziale per diagnosticare problemi in produzione
- **Test incrementali**: Provare una soluzione alla volta
- **Backup**: Fare backup prima di modifiche significative

---

**Ultimo aggiornamento**: 2025-07-24
**Stato**: Diagnosi locale completata, test produzione necessari 