# Guida Risoluzione Problema Microfono WebRTC

## Errore: "NotReadableError: Could not start audio source"

### **Passi per risolvere:**

#### **1. Verifica Applicazioni in Uso**
- **Chiudi tutte le applicazioni** che potrebbero usare il microfono:
  - Zoom, Teams, Discord, Skype
  - Browser con altre schede aperte
  - App di registrazione audio
  - Giochi con chat vocale

#### **2. Verifica Permessi Browser**
- **Clicca sull'icona del lucchetto** nella barra degli indirizzi
- **Verifica che il microfono sia "Consentito"**
- Se è "Bloccato", clicca e cambia in "Consentito"
- **Ricarica la pagina** (F5)

#### **3. Verifica Impostazioni Windows**
- **Apri Impostazioni Windows** > Sistema > Suono
- **Verifica il dispositivo di input** (microfono)
- **Testa il microfono** nelle impostazioni Windows
- **Assicurati che il volume non sia a 0**

#### **4. Verifica Impostazioni Privacy**
- **Impostazioni Windows** > Privacy > Microfono
- **Assicurati che "Consenti alle app di accedere al microfono" sia attivo**
- **Verifica che il browser sia nella lista delle app consentite**

#### **5. Reset Permessi Browser**
- **Apri Chrome/Firefox**
- **Vai su Impostazioni** > Privacy e sicurezza > Impostazioni sito
- **Cerca "slamin.local"**
- **Rimuovi i permessi** per microfono e camera
- **Ricarica la pagina** e riprova

#### **6. Verifica Dispositivo Audio**
- **Controlla che il microfono sia collegato**
- **Prova a parlare** nel microfono per vedere se rileva il suono
- **Verifica che non sia silenziato** (icona microfono barrata)

#### **7. Test Alternativo**
- **Prova con un altro browser** (Chrome, Firefox, Edge)
- **Prova con un altro dispositivo** (se disponibile)
- **Prova con un altro microfono** (se disponibile)

### **Comandi PowerShell per Diagnosi:**

```powershell
# Verifica dispositivi audio
Get-WmiObject -Class Win32_SoundDevice | Select-Object Name, Status

# Verifica servizi audio
Get-Service -Name Audiosrv, AudioEndpointBuilder | Select-Object Name, Status
```

### **Se il problema persiste:**

1. **Riavvia il browser** completamente
2. **Riavvia Windows** 
3. **Verifica i driver audio** sono aggiornati
4. **Prova in modalità provvisoria** per escludere conflitti software

### **Test Finale:**
- Vai su `https://slamin.local/test-microphone.html`
- Clicca "Test Microfono"
- Dovresti vedere "✓ Microfono funzionante"

**Se funziona qui, funzionerà anche nelle chiamate della chat!** 🎤✅ 