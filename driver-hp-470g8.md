# Driver Audio HP 470 G8 - Microfono Non Rilevato

## **Problema: Microfono non rilevato in Gestione Dispositivi**

### **Driver Necessari per HP 470 G8:**

#### **1. Driver Audio Realtek (PRIMARIO)**
- **Nome:** Realtek High Definition Audio Driver
- **Versione:** 6.0.9xxx o superiore
- **Scarica da:** [HP Support](https://support.hp.com/it-it/drivers/selfservice/hp-probook-470-g8-notebook-pc/25058107)

#### **2. Driver Intel Smart Sound Technology**
- **Nome:** Intel Smart Sound Technology Driver
- **Versione:** 10.29.0.11516 (quello che vedi nelle impostazioni)
- **Scarica da:** [HP Support](https://support.hp.com/it-it/drivers/selfservice/hp-probook-470-g8-notebook-pc/25058107)

#### **3. Driver Chipset Intel**
- **Nome:** Intel Chipset Installation Utility
- **Versione:** 10.1.18xxx
- **Scarica da:** [HP Support](https://support.hp.com/it-it/drivers/selfservice/hp-probook-470-g8-notebook-pc/25058107)

### **Passi di Installazione:**

#### **Passo 1: Scarica i Driver**
1. Vai su [HP Support](https://support.hp.com/it-it/drivers/selfservice/hp-probook-470-g8-notebook-pc/25058107)
2. **Seleziona il tuo sistema operativo** (Windows 10/11)
3. **Scarica questi driver:**
   - ✅ **Realtek High Definition Audio**
   - ✅ **Intel Smart Sound Technology**
   - ✅ **Intel Chipset Installation Utility**

#### **Passo 2: Disinstalla Driver Esistenti**
1. **Gestione Dispositivi** (Win + X)
2. **Espandi "Controller audio, video e giochi"**
3. **Tasto destro su ogni dispositivo audio** > **Disinstalla dispositivo**
4. **Spunta "Elimina il software driver per questo dispositivo"**
5. **Ripeti per tutti i dispositivi audio**

#### **Passo 3: Installa i Nuovi Driver**
1. **Installa prima Intel Chipset** (riavvia se richiesto)
2. **Installa Intel Smart Sound Technology**
3. **Installa Realtek High Definition Audio**
4. **Riavvia Windows**

#### **Passo 4: Verifica Installazione**
1. **Gestione Dispositivi** > **Controller audio**
2. **Dovresti vedere:**
   - ✅ Intel Smart Sound Technology
   - ✅ Realtek High Definition Audio
   - ✅ Microfono integrato
   - ✅ Altoparlanti

### **Se i Driver HP Non Funzionano:**

#### **Driver Realtek Ufficiali:**
1. Vai su [Realtek.com](https://www.realtek.com/en/component/zoo/category/pc-audio-codecs-high-definition-audio-codecs-software)
2. **Scarica Realtek High Definition Audio Codecs**
3. **Versione:** 6.0.9xxx o superiore

#### **Driver Intel Ufficiali:**
1. Vai su [Intel.com](https://www.intel.com/content/www/us/en/download/785597/intel-smart-sound-technology-intel-sst-driver-for-windows-10-64-bit.html)
2. **Scarica Intel Smart Sound Technology**
3. **Versione:** 10.29.0.11516

### **Comandi PowerShell per Diagnosi:**

```powershell
# Verifica dispositivi audio
Get-WmiObject -Class Win32_SoundDevice | Select-Object Name, Status, DeviceID

# Verifica driver audio
Get-WmiObject -Class Win32_PnPSignedDriver | Where-Object {$_.DeviceName -like "*audio*" -or $_.DeviceName -like "*sound*"} | Select-Object DeviceName, DriverVersion

# Verifica servizi audio
Get-Service -Name Audiosrv, AudioEndpointBuilder, Audiosrv | Select-Object Name, Status, StartType
```

### **Se Ancora Non Funziona:**

#### **1. BIOS/UEFI Settings:**
1. **Riavvia e premi F10** per entrare nel BIOS
2. **Advanced** > **Device Options**
3. **Verifica che "Audio Controller" sia Enabled**
4. **Salva e riavvia**

#### **2. Windows Audio Troubleshooter:**
1. **Impostazioni** > **Sistema** > **Audio**
2. **Risoluzione problemi audio**
3. **Esegui il troubleshooter**

#### **3. Reset Audio Windows:**
```powershell
# Esegui come Amministratore
net stop audiosrv
net stop AudioEndpointBuilder
net start audiosrv
net start AudioEndpointBuilder
```

### **Test Finale:**
1. **Riavvia Windows**
2. **Vai su** `https://slamin.local/test-microphone.html`
3. **Clicca "Test Microfono"**
4. **Dovresti vedere "✓ Microfono funzionante"**

### **Supporto HP:**
- **Numero:** 800-130-000 (Italia)
- **Chat:** [HP Support](https://support.hp.com/it-it/contact-hp)
- **Forum:** [HP Community](https://h30434.www3.hp.com/)

**Il microfono HP 470 G8 è integrato e funziona perfettamente con i driver corretti!** 🎤✅ 