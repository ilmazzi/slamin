# 💰 Sistema di Pagamenti - Spiegazione Completa

## 🔄 **Come Funziona il Flusso dei Pagamenti**

### **1. Flusso Principale**

```
Poeta → Piattaforma → Traduttore
```

**Dettaglio del processo:**

1. **Poeta paga** → I soldi vanno **sul tuo account Stripe/PayPal**
2. **Piattaforma trattiene** la commissione (es. 10%)
3. **Piattaforma trasferisce** automaticamente il resto al traduttore

### **2. Dove Vanno i Soldi**

#### **Stripe:**
- I soldi vanno sul **tuo account Stripe principale**
- Trasferimento automatico al traduttore via **Stripe Connect**
- Se il traduttore non ha Stripe Connect → **trasferimento manuale**

#### **PayPal:**
- I soldi vanno sul **tuo account PayPal principale**
- Trasferimento via **PayPal Mass Payments** o manuale

## 🏗️ **Architettura del Sistema**

### **Database Schema**

#### **Translation Payments:**
```sql
- amount: Importo totale pagato dal poeta
- commission_total: Commissione della piattaforma
- translator_amount: Importo che va al traduttore
- platform_amount: Importo che rimane alla piattaforma
- payout_status: pending, transferred, failed, manual_required
- payout_transfer_id: ID del trasferimento Stripe
- payout_date: Data del trasferimento
```

#### **Users:**
```sql
- stripe_connect_account_id: Account Stripe Connect del traduttore
- paypal_email: Email PayPal del traduttore
- payout_method_configured: Se ha configurato un metodo di payout
```

## 🔧 **Configurazione Stripe Connect**

### **Per i Traduttori:**

1. **Registrazione Stripe Connect:**
   - Il traduttore si registra su Stripe Connect
   - Ottiene un `connect_account_id`
   - Lo inserisce nel suo profilo

2. **Trasferimento Automatico:**
   - Quando il poeta paga, Stripe trasferisce automaticamente
   - Il traduttore riceve i soldi sul suo account Stripe
   - La piattaforma trattiene la commissione

### **Per la Piattaforma:**

1. **Account Stripe Principale:**
   - Il tuo account Stripe riceve tutti i pagamenti
   - Gestisce le commissioni
   - Trasferisce ai traduttori

2. **Webhook:**
   - Conferma automatica dei pagamenti
   - Avvia il trasferimento al traduttore
   - Aggiorna lo stato nel database

## 📊 **Dashboard Admin**

### **Funzionalità Disponibili:**

1. **Monitoraggio Pagamenti:**
   - Lista di tutti i pagamenti
   - Stato dei payout
   - Statistiche commissioni

2. **Gestione Payout:**
   - Trasferimento automatico
   - Payout manuali
   - Gestione errori

3. **Statistiche:**
   - Revenue totale
   - Commissioni incassate
   - Importi trasferiti

## 💡 **Vantaggi del Sistema**

### **Per la Piattaforma:**
- ✅ **Controllo completo** sui pagamenti
- ✅ **Commissioni automatiche**
- ✅ **Trasferimenti automatici**
- ✅ **Monitoraggio completo**

### **Per i Traduttori:**
- ✅ **Pagamenti automatici**
- ✅ **Nessun ritardo**
- ✅ **Trasparenza totale**
- ✅ **Supporto multipli metodi**

### **Per i Poeti:**
- ✅ **Pagamento sicuro**
- ✅ **Supporto carte e PayPal**
- ✅ **Conferma immediata**
- ✅ **Protezione acquisti**

## 🚀 **Setup Completo**

### **1. Configurazione Account:**

```env
# Stripe
STRIPE_KEY=pk_live_your_publishable_key
STRIPE_SECRET=sk_live_your_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret

# PayPal
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_CLIENT_SECRET=your_paypal_client_secret
PAYPAL_MODE=live
```

### **2. Webhook Stripe:**
- URL: `https://slamin.local/webhook/stripe`
- Eventi: `payment_intent.succeeded`, `payment_intent.payment_failed`

### **3. Stripe Connect:**
- Abilita Stripe Connect nel tuo account
- Configura le commissioni
- Imposta i trasferimenti automatici

## 📈 **Monetizzazione**

### **Commissioni Configurabili:**
- **Percentuale:** Es. 10% su ogni pagamento
- **Fisso:** Es. €2 per ogni pagamento
- **Misto:** Percentuale + fisso

### **Esempio di Calcolo:**
```
Pagamento Poeta: €50
Commissione 10%: €5
Al Traduttore: €45
Alla Piattaforma: €5
```

## 🔒 **Sicurezza**

### **Protezioni Implementate:**
- ✅ **Verifica webhook** Stripe
- ✅ **Validazione pagamenti** con API
- ✅ **Logging completo** di tutte le operazioni
- ✅ **Gestione errori** robusta
- ✅ **Controllo accessi** admin

## 📱 **Interfaccia Utente**

### **Per i Traduttori:**
- Configurazione metodo di payout
- Storico pagamenti ricevuti
- Notifiche automatiche

### **Per i Poeti:**
- Pagamento con carte o PayPal
- Conferma immediata
- Storico pagamenti effettuati

### **Per gli Admin:**
- Dashboard completa
- Gestione payout
- Statistiche dettagliate

## 🎯 **Risultato Finale**

Il sistema è **completamente automatizzato**:
1. **Poeta paga** → Soldi sul tuo account
2. **Webhook conferma** → Pagamento verificato
3. **Trasferimento automatico** → Traduttore riceve i soldi
4. **Commissione trattenuta** → Tu guadagni

**Nessun intervento manuale richiesto!** 🚀
