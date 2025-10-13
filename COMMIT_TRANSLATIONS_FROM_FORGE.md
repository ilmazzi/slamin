# 🚀 Come Committare Traduzioni da Laravel Forge

## Opzione 1: Tramite SSH (RACCOMANDATO)

### Passo 1: Connettiti via SSH
```bash
# Usa il comando SSH che trovi nel pannello Forge
ssh forge@YOUR_SERVER_IP
```

### Passo 2: Vai nella Directory del Progetto
```bash
cd /home/forge/slamin.it
# oppure
cd /home/forge/default
# (controlla il path nel pannello Forge)
```

### Passo 3: Verifica le Modifiche
```bash
git status
```

### Passo 4: Aggiungi e Committa
```bash
git add lang/*
git commit -m "Update translations from admin panel"
git push origin main
```

---

## Opzione 2: Tramite Pannello Forge

### Se hai accesso al pannello Forge:

1. **Vai su Sites → Il tuo sito → Commands**
2. **Crea un nuovo comando:**
   ```bash
   cd /home/forge/slamin.it
   git add lang/*
   git commit -m "Update translations from admin panel"  
   git push origin main
   ```
3. **Esegui il comando**

---

## Opzione 3: Script di Deploy

Crea un file deploy script in Forge:

```bash
cd /home/forge/slamin.it

# Committa eventuali modifiche alle traduzioni
if [ -n "$(git status --porcelain lang/)" ]; then
    git add lang/*
    git commit -m "Update translations - $(date '+%Y-%m-%d %H:%M:%S')"
    git push origin main
fi

# Poi fai pull delle nuove modifiche
git pull origin main
```

---

## Opzione 4: Configurare Auto-Commit

Se vuoi automatizzare, puoi creare un cron job:

```bash
# Cron che committa traduzioni ogni giorno alle 3:00 AM
0 3 * * * cd /home/forge/slamin.it && [ -n "$(git status --porcelain lang/)" ] && git add lang/* && git commit -m "Auto: Update translations" && git push origin main
```

---

## ⚠️ IMPORTANTE

Prima di fare push, assicurati che l'utente Git sia configurato:

```bash
ssh forge@YOUR_SERVER_IP
cd /home/forge/slamin.it

# Configura Git user (se non già fatto)
git config user.name "Your Name"
git config user.email "your-email@example.com"

# Verifica
git config --list | grep user
```

---

## 🔧 Risoluzione Problemi

### Se ricevi "Permission denied"
```bash
# Verifica che l'utente forge abbia permessi
ls -la /home/forge/slamin.it/.git

# Se necessario, ripristina permessi
chown -R forge:forge /home/forge/slamin.it/.git
```

### Se ricevi "Please tell me who you are"
```bash
git config user.name "Forge Deployment"
git config user.email "forge@slamin.it"
```

### Se ricevi errori di autenticazione GitHub
Forge dovrebbe già avere la chiave SSH configurata, ma se necessario:
1. Vai nel pannello Forge → Source Control
2. Verifica che la chiave SSH sia collegata al repository GitHub

---

## ✅ Dopo il Commit da Produzione

Una volta committato e pushato da produzione:

```bash
# Torna qui in locale
cd /Users/mazzi/Documents/GitHub/slamin
git pull origin main
```

Questo scaricherà tutte le traduzioni aggiornate! 🎉
