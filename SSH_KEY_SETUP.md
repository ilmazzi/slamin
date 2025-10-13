# 🔐 Creazione Chiave SSH per Laravel Forge

## 📋 Step 1: Crea una Nuova Chiave SSH

### Sul tuo Mac, esegui:

```bash
# Genera una nuova chiave SSH (usa il tuo email)
ssh-keygen -t ed25519 -C "your-email@example.com"

# Ti chiederà dove salvare (premi ENTER per default)
# Ti chiederà una passphrase (opzionale, ma raccomandato)
```

**Esempio:**
```bash
ssh-keygen -t ed25519 -C "mazzi@slamin.it"
```

### Risposte alle Domande:
1. **File location:** Premi ENTER (usa default: `~/.ssh/id_ed25519`)
2. **Passphrase:** Inserisci una password sicura (o premi ENTER per nessuna)

---

## 📋 Step 2: Aggiungi la Chiave al SSH Agent

```bash
# Avvia il SSH agent
eval "$(ssh-agent -s)"

# Aggiungi la tua chiave
ssh-add ~/.ssh/id_ed25519
```

---

## 📋 Step 3: Copia la Chiave Pubblica

```bash
# Copia la chiave pubblica (la mostrerà a schermo)
cat ~/.ssh/id_ed25519.pub

# Oppure copia direttamente negli appunti (macOS)
pbcopy < ~/.ssh/id_ed25519.pub
```

---

## 📋 Step 4: Aggiungi la Chiave a Forge

### Nel Pannello Forge:

1. **Vai su "Servers"** → Il tuo server "Slamin"
2. **Cerca "SSH Keys"** o **"Server Keys"**
3. **Clicca "Add SSH Key"**
4. **Incolla la chiave pubblica** (quella che hai copiato)
5. **Dai un nome** (es. "Mazzi MacBook")
6. **Salva**

---

## 📋 Step 5: Testa la Connessione

```bash
# Sostituisci IP_DEL_TUO_SERVER con l'IP reale
ssh forge@IP_DEL_TUO_SERVER

# Prima volta ti chiederà di confermare il fingerprint
# Rispondi "yes"
```

---

## 🔧 Se Hai Problemi

### Problema: "Permission denied (publickey)"
```bash
# Verifica che la chiave sia caricata
ssh-add -l

# Se vuota, ricarica
ssh-add ~/.ssh/id_ed25519
```

### Problema: "Could not open connection"
- Verifica l'IP del server
- Controlla che il server sia online

### Problema: Chiave non riconosciuta
- Assicurati di aver aggiunto la chiave **pubblica** (`.pub`) a Forge
- Non la chiave **privata** (`id_ed25519`)

---

## ✅ Una Volta Connesso

```bash
# Vai nella directory del progetto
cd /home/forge/slamin.it

# Verifica Git
git status

# Se ci sono modifiche alle traduzioni:
git add lang/*
git commit -m "Update translations from admin panel"
git push origin main
```

---

## 🔑 File Chiave SSH

- **Chiave Privata:** `~/.ssh/id_ed25519` (NON condividere mai!)
- **Chiave Pubblica:** `~/.ssh/id_ed25519.pub` (questa vai a Forge)

---

## 🎯 Comandi Rapidi

```bash
# Genera chiave
ssh-keygen -t ed25519 -C "your-email@example.com"

# Mostra chiave pubblica
cat ~/.ssh/id_ed25519.pub

# Test connessione
ssh forge@IP_DEL_TUO_SERVER
```
