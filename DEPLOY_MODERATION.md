# 🚀 Deploy Sistema Moderazione - Poetry Slam

## 📋 Panoramica

Questo documento descrive come deployare il sistema di moderazione in produzione per il progetto Poetry Slam.

## ✅ Prerequisiti

- ✅ Laravel 10+ installato
- ✅ Database configurato e accessibile
- ✅ Spatie Permission package installato
- ✅ Tutte le migrazioni eseguite

## 🚀 Deploy Automatico

### 1. Comando di Deploy

```bash
php artisan deploy:moderation --force
```

Questo comando esegue automaticamente:
- ✅ Verifica migrazioni
- ✅ Configurazione impostazioni di produzione
- ✅ Creazione ruoli e permessi
- ✅ Creazione conversazioni mancanti
- ✅ Verifica sistema

### 2. Deploy Manuale (se necessario)

```bash
# 1. Esegui migrazioni
php artisan migrate

# 2. Esegui seeder di produzione
php artisan db:seed --class=ProductionModerationSeeder

# 3. Pulisci cache
php artisan view:clear
php artisan config:clear
php artisan route:clear
```

## ⚙️ Configurazioni di Produzione

### Auto-Approval Settings
- **Videos**: Disabled (richiede approvazione)
- **Poems**: Disabled (richiede approvazione)
- **Events**: Disabled (richiede approvazione)
- **Photos**: Disabled (richiede approvazione)
- **Articles**: Disabled (richiede approvazione)
- **Carousels**: Disabled (richiede approvazione)
- **Comments**: Disabled (richiede approvazione)

### Notification Settings
- **Email Notifications**: Enabled
- **Items per Page**: 20
- **Reports Retention**: 30 giorni

### Moderation Settings
- **Moderation Enabled**: Yes
- **Require Approval**: Yes
- **Auto Delete Rejected**: No
- **Retention Days**: 90

## 👥 Ruoli e Permessi

### Ruolo Admin
- ✅ Tutti i permessi di moderazione
- ✅ Gestione impostazioni
- ✅ Accesso completo al sistema

### Ruolo Moderator
- ✅ Visualizzare dashboard moderazione
- ✅ Approvare contenuti
- ✅ Rifiutare contenuti
- ✅ Mettere in investigazione
- ✅ Gestire segnalazioni
- ✅ Gestire conversazioni moderazione
- ❌ Gestire impostazioni (solo admin)

## 🔗 URL di Test

### Dashboard Moderazione
```
/admin/moderation
```

### Conversazioni
```
/moderation/conversation/{report_id}
```

### Impostazioni
```
/admin/moderation/settings
```

## 📊 Statistiche Sistema

Dopo il deploy, il sistema dovrebbe avere:
- ✅ **Reports**: 5+ segnalazioni
- ✅ **Conversations**: 5+ conversazioni
- ✅ **Messages**: 6+ messaggi
- ✅ **Roles**: Admin, Moderator
- ✅ **Permissions**: 7 permessi di moderazione

## 🧪 Test del Sistema

### 1. Test Segnalazioni
1. Vai su una pagina di contenuto (video, articolo, poesia)
2. Clicca "Segnala"
3. Compila il form
4. Verifica che la segnalazione appaia in `/admin/moderation`

### 2. Test Conversazioni
1. Vai su `/admin/moderation`
2. Clicca "Conversazione" su una segnalazione
3. Invia un messaggio
4. Verifica che l'autore riceva la notifica

### 3. Test Moderazione
1. Vai su `/admin/moderation/pending`
2. Approva o rifiuta contenuti
3. Verifica che lo status cambi correttamente

## 🔧 Configurazione Post-Deploy

### 1. Assegnare Ruoli
```bash
# Assegna ruolo admin a un utente
php artisan tinker
$user = User::find(1);
$user->assignRole('admin');
```

### 2. Modificare Auto-Approval
```bash
# Abilita auto-approval per contenuti specifici
php artisan tinker
SystemSetting::set('moderation.videos.auto_approve', true);
```

### 3. Configurare Email
Verifica che le notifiche email siano configurate in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Poetry Slam"
```

## 🚨 Troubleshooting

### Errore: "Conversazione non trovata"
```bash
# Ricrea conversazioni mancanti
php artisan deploy:moderation --force
```

### Errore: "Non hai i permessi"
```bash
# Verifica ruoli utente
php artisan tinker
$user = User::find(1);
$user->roles->pluck('name');
```

### Errore: "Route non trovata"
```bash
# Pulisci cache route
php artisan route:clear
php artisan config:clear
```

## 📞 Supporto

Per problemi o domande:
1. Controlla i log: `storage/logs/laravel.log`
2. Verifica configurazioni: `/admin/moderation/settings`
3. Testa funzionalità: `/admin/moderation`

---

**✅ Sistema pronto per la produzione!** 🎉
