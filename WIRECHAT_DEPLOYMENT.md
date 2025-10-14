# Wirechat Deployment Guide

## Problema Deploy

Se il deploy fallisce con errore:
```
Wirechat\Wirechat\Helpers\MorphClassResolver::encode(): Argument #1 ($rawType) must be of type string, null given
```

## Soluzione

Sul server di produzione, eseguire **IN QUESTO ORDINE**:

### 1. Pulire lo stato Git
```bash
cd /home/forge/slamin.it
git reset --hard
git clean -fd
```

### 2. Pull delle modifiche
```bash
git pull origin main
```

### 3. Installare dipendenze Composer
```bash
composer install --no-dev --optimize-autoloader
```

### 4. Eseguire le migrations di Wirechat
```bash
php artisan migrate --force
```

### 5. Pulire e ottimizzare
```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Compilare gli assets
```bash
npm install
npm run build
```

## Note

- L'errore si verifica perché il `WirechatServiceProvider` cerca di registrare i morph maps prima che le tabelle esistano
- Le migrations di Wirechat DEVONO essere eseguite PRIMA di ottimizzare le configurazioni
- Il morph map è configurato in `App\Providers\AppServiceProvider` con un try-catch per evitare errori

## Ordine di Deploy Corretto

1. ✅ Git pull
2. ✅ Composer install
3. ✅ Migrate (IMPORTANTE: prima di config:cache!)
4. ✅ Config/route/view cache
5. ✅ NPM build

