# 🎯 Comandi da Eseguire via SSH su Forge

## 📋 Comandi Step by Step

### 1. Vai nella Directory del Progetto
```bash
cd /home/forge/slamin.it
```

### 2. Verifica le Modifiche
```bash
git status
```

### 3. Se vedi modifiche ai file lang/:
```bash
# Aggiungi tutti i file di traduzione
git add lang/*

# Committa con un messaggio
git commit -m "Update translations from admin panel"

# Pusha su GitHub
git push origin main
```

### 4. Se NON vedi modifiche:
```bash
# Controlla se ci sono file non tracciati
git status --ignored

# Oppure controlla direttamente i file
ls -la lang/it/
```

---

## 🔍 Comandi di Verifica

### Controlla le Date dei File
```bash
ls -la lang/it/*.php
```

### Controlla le Ultime Modifiche
```bash
find lang/ -name "*.php" -mtime -7 -ls
```

### Controlla Git Log
```bash
git log --oneline -10
```

---

## ✅ Dopo il Push

Una volta che hai pushato da produzione, torna qui in locale e fai:

```bash
cd /Users/mazzi/Documents/GitHub/slamin
git pull origin main
```

---

## 🚨 Se Git Non è Configurato

Se ricevi errori di configurazione Git:

```bash
# Configura Git user
git config user.name "Forge Deployment"
git config user.email "forge@slamin.it"

# Verifica
git config --list | grep user
```

---

## 💡 Comandi Rapidi

```bash
# Tutto in una volta
cd /home/forge/slamin.it && git status && git add lang/* && git commit -m "Update translations" && git push origin main
```
