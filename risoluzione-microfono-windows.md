# Risoluzione Microfono Silenziato in Windows

## **Problema: Volume microfono bloccato a 0%**

### **Soluzione 1: Impostazioni Audio Windows**

1. **Apri Impostazioni Windows** (Win + I)
2. **Sistema** > **Audio**
3. **Trova il tuo microfono** nella sezione "Input"
4. **Clicca sulle proprietà** del microfono
5. **Verifica che:**
   - ✅ "Consenti alle app di usare questo dispositivo" sia ATTIVO
   - ✅ Il volume non sia a 0%
   - ✅ Non ci sia l'icona del microfono barrata

### **Soluzione 2: Pannello di Controllo Audio**

1. **Apri Pannello di Controllo** (Win + R, digita `control`)
2. **Hardware e suoni** > **Suoni**
3. **Scheda Registrazione**
4. **Tasto destro sul microfono** > **Proprietà**
5. **Scheda Livelli:**
   - ✅ Alza il volume del microfono
   - ✅ Rimuovi il simbolo 🔇 (muto)
6. **Scheda Avanzate:**
   - ✅ Deseleziona "Consenti alle applicazioni di prendere il controllo esclusivo"

### **Soluzione 3: Gestione Dispositivi**

1. **Apri Gestione Dispositivi** (Win + X > Gestione dispositivi)
2. **Espandi "Controller audio, video e giochi"**
3. **Tasto destro sul controller audio** > **Disinstalla dispositivo**
4. **Riavvia Windows**
5. **Windows reinstallerà automaticamente i driver**

### **Soluzione 4: Comandi PowerShell (Amministratore)**

```powershell
# Verifica servizi audio
Get-Service -Name Audiosrv, AudioEndpointBuilder | Start-Service

# Verifica dispositivi audio
Get-WmiObject -Class Win32_SoundDevice | Select-Object Name, Status

# Reset servizi audio
Stop-Service -Name Audiosrv, AudioEndpointBuilder
Start-Service -Name Audiosrv, AudioEndpointBuilder
```

### **Soluzione 5: Troubleshooter Windows**

1. **Impostazioni** > **Aggiornamento e sicurezza** > **Risoluzione problemi**
2. **Trova e risolvi altri problemi**
3. **Riproduzione audio** > **Esegui il troubleshooter**
4. **Registrazione audio** > **Esegui il troubleshooter**

### **Soluzione 6: Driver Audio**

1. **Gestione Dispositivi** > **Controller audio**
2. **Tasto destro sul controller** > **Aggiorna driver**
3. **Cerca automaticamente i driver aggiornati**
4. **Se non funziona, scarica manualmente dal sito del produttore**

### **Soluzione 7: Impostazioni Privacy**

1. **Impostazioni** > **Privacy** > **Microfono**
2. ✅ **"Consenti alle app di accedere al microfono"**
3. ✅ **"Consenti alle app desktop di accedere al microfono"**
4. **Scorri in basso e verifica che il browser sia nella lista**

### **Test Rapido:**

1. **Apri Blocco Note**
2. **Inserisci** > **Riconoscimento vocale**
3. **Parla nel microfono** - dovresti vedere il testo apparire
4. **Se non funziona, il problema è nelle impostazioni Windows**

### **Se Nulla Funziona:**

1. **Riavvia Windows**
2. **Prova in modalità provvisoria**
3. **Ripristina Windows** (ultima risorsa)

### **Test Finale:**

Dopo aver risolto:
- Vai su `https://slamin.local/test-microphone.html`
- Clicca "Test Microfono"
- Dovresti vedere "✓ Microfono funzionante"

**Il problema è 100% risolvibile!** 🎤✅ 