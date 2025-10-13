# 🔐 Come Connettersi via SSH a Laravel Forge

## 📍 Dove Trovare i Dati SSH

### Nel Pannello Forge:

1. **Vai su "Servers"** (lista dei tuoi server)
2. **Clicca sul tuo server** (probabilmente chiamato "Slamin")
3. **Nella pagina del server, cerca:**
   - **"Server Details"** o **"Connection Details"**
   - **IP Address** del server
   - **Username** (di solito `forge`)

---

## 🔑 Comando SSH

Il comando SSH sarà simile a questo:

```bash
ssh forge@IP_DEL_TUO_SERVER
```

**Esempio:**
```bash
ssh forge@123.456.789.123
```

---

## 📋 Step by Step

### 1. Trova l'IP del Server
Nel pannello Forge:
- Vai su **Servers**
- Clicca sul tuo server
- Copia l'**IP Address** (es. `123.456.789.123`)

### 2. Usa il Comando SSH
```bash
ssh forge@123.456.789.123
```

### 3. Prima Connessione
Ti chiederà:
```
The authenticity of host '123.456.789.123' can't be established.
ECDSA key fingerprint is SHA256:xxxxx
Are you sure you want to continue connecting (yes/no)?
```
**Rispondi:** `yes`

### 4. Password
Se ti chiede una password, prova:
- `forge` (username)
- La password che hai impostato per Forge

---

## 🚨 Se Non Funziona

### Problema: "Permission denied"
```bash
# Prova con root invece di forge
ssh root@IP_DEL_TUO_SERVER
```

### Problema: "Connection refused"
- Verifica che l'IP sia corretto
- Controlla che il server sia online nel pannello Forge

### Problema: Chiave SSH
Se hai impostato chiavi SSH personali, potresti dover specificare:
```bash
ssh -i /path/to/your/private/key forge@IP_DEL_TUO_SERVER
```

---

## 🎯 Una Volta Connesso

```bash
# Vai nella directory del progetto
cd /home/forge/slamin.it

# Verifica le modifiche alle traduzioni
git status

# Se ci sono modifiche ai file lang/:
git add lang/*
git commit -m "Update translations from admin panel"
git push origin main
```

---

## 💡 Alternative se SSH Non Funziona

### Opzione 1: Console di Forge
Nel pannello Forge:
- Vai su **Sites** → Il tuo sito
- Clicca su **Commands** 
- Esegui i comandi Git direttamente lì

### Opzione 2: File Manager di Forge
- Usa il file manager integrato
- Modifica i file direttamente
- Usa i comandi Git dalla console

---

## 🔍 Come Trovare l'IP nel Pannello Forge

1. **Login su forge.laravel.com**
2. **Clicca su "Servers"** nel menu laterale
3. **Clicca sul tuo server** (Slamin)
4. **Nella dashboard del server, cerca:**
   - "Server Details"
   - "IP Address" o "Public IP"
   - Dovrebbe essere qualcosa come: `123.456.789.123`

---

## ✅ Test Rapido

Una volta che hai l'IP, prova questo comando:

```bash
# Sostituisci IP_DEL_TUO_SERVER con l'IP reale
ssh forge@IP_DEL_TUO_SERVER "pwd && ls -la"
```

Se funziona, vedrai la directory home e i file! 🎉
