# 📋 Analisi Sistema Notifiche - Slam In

## 🎯 Obiettivo
Rifare completamente il sistema notifiche con Livewire 3, organizzazione chiara e UX moderna.

---

## 📊 NOTIFICHE ESISTENTI NEL SISTEMA

### 🎪 **EVENTI** (10 tipi)

#### Inviti
1. **event_invitation** - Invito a partecipare a evento
   - Destinatario: Utente invitato
   - Trigger: Organizzatore invia invito
   - Dati: event_id, invitation_id, role, compensation
   - Azione: "Gestisci Invito" → /invitations
   - Priorità: HIGH
   - Email: ✅ SI

2. **invitation_accepted** - Invito accettato
   - Destinatario: Organizzatore evento
   - Trigger: Utente accetta invito
   - Dati: event_id, user_id, role
   - Azione: "Vedi Evento" → /events/{id}
   - Priorità: NORMAL
   - Email: ❌ NO

3. **invitation_declined** - Invito rifiutato
   - Destinatario: Organizzatore evento
   - Trigger: Utente rifiuta invito
   - Dati: event_id, user_id, role
   - Azione: "Vedi Evento" → /events/{id}
   - Priorità: NORMAL
   - Email: ❌ NO

#### Richieste di Partecipazione
4. **new_request** - Nuova richiesta partecipazione
   - Destinatario: Organizzatore evento
   - Trigger: Utente richiede partecipazione
   - Dati: event_id, request_id, user_id, role
   - Azione: "Gestisci Richieste" → /events/{id}/requests
   - Priorità: HIGH
   - Email: ✅ SI (se preferenze utente)

5. **request_accepted** - Richiesta accettata
   - Destinatario: Utente richiedente
   - Trigger: Organizzatore accetta richiesta
   - Dati: event_id, request_id
   - Azione: "Vedi Evento" → /events/{id}
   - Priorità: HIGH
   - Email: ✅ SI

6. **request_declined** - Richiesta rifiutata
   - Destinatario: Utente richiedente
   - Trigger: Organizzatore rifiuta richiesta
   - Dati: event_id, request_id
   - Azione: "Vedi Evento" → /events/{id}
   - Priorità: NORMAL
   - Email: ❌ NO

7. **request_cancelled** - Richiesta cancellata
   - Destinatario: Organizzatore evento
   - Trigger: Utente cancella propria richiesta
   - Dati: event_id, user_id
   - Azione: "Vedi Richieste" → /events/{id}/requests
   - Priorità: LOW
   - Email: ❌ NO

#### Aggiornamenti
8. **event_update** - Evento aggiornato
   - Destinatario: Tutti i partecipanti confermati
   - Trigger: Organizzatore modifica evento
   - Dati: event_id, changes[], custom_message
   - Azione: "Vedi Modifiche" → /events/{id}
   - Priorità: HIGH se cambi importanti, altrimenti NORMAL
   - Email: ✅ SI (se cambi importanti)

9. **event_cancelled** - Evento cancellato
   - Destinatario: Tutti i partecipanti confermati
   - Trigger: Organizzatore cancella evento
   - Dati: event_id, reason
   - Azione: "Vedi Dettagli" → /events/{id}
   - Priorità: URGENT
   - Email: ✅ SI (sempre)

10. **event_reminder** - Promemoria evento
    - Destinatario: Tutti i partecipanti confermati
    - Trigger: Automatico (24h e 2h prima)
    - Dati: event_id, hours_until
    - Azione: "Vedi Evento" → /events/{id}
    - Priorità: HIGH se 2h, NORMAL se 24h
    - Email: ✅ SI

---

### 👥 **GRUPPI** (9 tipi)

#### Inviti
11. **group_invitation** - Invito a gruppo
    - Destinatario: Utente invitato
    - Trigger: Admin/Owner invia invito
    - Dati: group_id, invitation_id, role
    - Azione: "Gestisci Invito" → /groups/{id}
    - Priorità: NORMAL
    - Email: ✅ SI

12. **group_invitation_accepted** - Invito gruppo accettato
    - Destinatario: Admin/Owner che ha invitato
    - Trigger: Utente accetta invito
    - Dati: group_id, user_id
    - Azione: "Vedi Gruppo" → /groups/{id}
    - Priorità: LOW
    - Email: ❌ NO

13. **group_invitation_declined** - Invito gruppo rifiutato
    - Destinatario: Admin/Owner che ha invitato
    - Trigger: Utente rifiuta invito
    - Dati: group_id, user_id
    - Azione: "Vedi Gruppo" → /groups/{id}
    - Priorità: LOW
    - Email: ❌ NO

#### Richieste Partecipazione
14. **group_join_request** - Richiesta partecipazione gruppo
    - Destinatario: Admin/Owner gruppo
    - Trigger: Utente richiede di entrare
    - Dati: group_id, request_id, user_id
    - Azione: "Gestisci Richieste" → /groups/{id}/requests
    - Priorità: NORMAL
    - Email: ✅ SI

15. **group_join_request_accepted** - Richiesta accettata
    - Destinatario: Utente richiedente
    - Trigger: Admin accetta richiesta
    - Dati: group_id
    - Azione: "Vedi Gruppo" → /groups/{id}
    - Priorità: NORMAL
    - Email: ✅ SI

16. **group_join_request_declined** - Richiesta rifiutata
    - Destinatario: Utente richiedente
    - Trigger: Admin rifiuta richiesta
    - Dati: group_id
    - Azione: "Vedi Gruppo" → /groups/{id}
    - Priorità: LOW
    - Email: ❌ NO

#### Attività Gruppo
17. **group_member_joined** - Nuovo membro
    - Destinatario: Tutti i membri gruppo
    - Trigger: Nuovo utente entra nel gruppo
    - Dati: group_id, user_id, user_name
    - Azione: "Vedi Gruppo" → /groups/{id}
    - Priorità: LOW
    - Email: ❌ NO

18. **group_member_left** - Membro uscito
    - Destinatario: Admin/Owner gruppo
    - Trigger: Utente lascia il gruppo
    - Dati: group_id, user_id, user_name
    - Azione: "Vedi Gruppo" → /groups/{id}
    - Priorità: LOW
    - Email: ❌ NO

19. **group_role_changed** - Ruolo cambiato
    - Destinatario: Utente con ruolo modificato
    - Trigger: Admin cambia ruolo
    - Dati: group_id, old_role, new_role
    - Azione: "Vedi Gruppo" → /groups/{id}
    - Priorità: NORMAL
    - Email: ✅ SI

#### Annunci
20. **group_announcement_created** - Annuncio gruppo privato
    - Destinatario: Tutti i membri gruppo
    - Trigger: Admin crea annuncio
    - Dati: group_id, announcement_id
    - Azione: "Leggi Annuncio" → /groups/{id}/announcements/{announcement_id}
    - Priorità: NORMAL
    - Email: ✅ SI (se preferenze utente)

21. **public_group_announcement_created** - Annuncio gruppo pubblico
    - Destinatario: Follower del gruppo
    - Trigger: Admin crea annuncio pubblico
    - Dati: group_id, announcement_id
    - Azione: "Leggi Annuncio" → /groups/{id}/announcements/{announcement_id}
    - Priorità: LOW
    - Email: ❌ NO

---

### 💼 **GIGS/INGAGGI** (7 tipi)

22. **gig_application** - Nuova candidatura
    - Destinatario: Creatore del gig
    - Trigger: Artista si candida
    - Dati: gig_id, application_id, user_id
    - Azione: "Gestisci Candidature" → /gigs/{id}/applications
    - Priorità: HIGH
    - Email: ✅ SI

23. **gig_application_accepted** - Candidatura accettata
    - Destinatario: Artista candidato
    - Trigger: Creatore accetta candidatura
    - Dati: gig_id, application_id
    - Azione: "Vedi Gig" → /gigs/{id}
    - Priorità: HIGH
    - Email: ✅ SI

24. **gig_application_rejected** - Candidatura rifiutata
    - Destinatario: Artista candidato
    - Trigger: Creatore rifiuta candidatura
    - Dati: gig_id, application_id
    - Azione: "Vedi Gig" → /gigs/{id}
    - Priorità: NORMAL
    - Email: ❌ NO

25. **gig_application_withdrawn** - Candidatura ritirata
    - Destinatario: Creatore gig
    - Trigger: Artista ritira candidatura
    - Dati: gig_id, user_id
    - Azione: "Vedi Gig" → /gigs/{id}
    - Priorità: LOW
    - Email: ❌ NO

26. **gig_closed** - Gig chiuso
    - Destinatario: Tutti i candidati
    - Trigger: Creatore chiude gig
    - Dati: gig_id
    - Azione: "Vedi Gig" → /gigs/{id}
    - Priorità: NORMAL
    - Email: ✅ SI

27. **gig_reopened** - Gig riaperto
    - Destinatario: Artisti interessati (bookmark/watchlist)
    - Trigger: Creatore riapre gig
    - Dati: gig_id
    - Azione: "Candidati" → /gigs/{id}
    - Priorità: NORMAL
    - Email: ❌ NO

28. **gig_shared** - Gig condiviso
    - Destinatario: Utente destinatario condivisione
    - Trigger: Utente condivide gig
    - Dati: gig_id, shared_by_user_id
    - Azione: "Vedi Gig" → /gigs/{id}
    - Priorità: LOW
    - Email: ❌ NO

29. **gig_global_message** - Messaggio globale gig
    - Destinatario: Tutti i candidati
    - Trigger: Creatore invia messaggio
    - Dati: gig_id, message
    - Azione: "Vedi Messaggio" → /gigs/{id}
    - Priorità: NORMAL
    - Email: ✅ SI

---

### ❤️ **SOCIAL/INTERAZIONI** (4 tipi)

30. **content_liked** - Mi piace su contenuto
    - Destinatario: Creatore contenuto
    - Trigger: Utente mette like
    - Dati: content_type, content_id, liker_id
    - Azione: "Vedi Contenuto" → dynamic
    - Priorità: LOW
    - Email: ❌ NO

31. **content_commented** - Commento su contenuto
    - Destinatario: Creatore contenuto
    - Trigger: Utente commenta
    - Dati: content_type, content_id, comment_id, commenter_id
    - Azione: "Vedi Commento" → dynamic
    - Priorità: NORMAL
    - Email: ❌ NO (troppi)

32. **comment_liked** - Like su commento
    - Destinatario: Autore commento
    - Trigger: Utente like su commento
    - Dati: comment_id, content_type, content_id, liker_id
    - Azione: "Vedi Commento" → dynamic
    - Priorità: LOW
    - Email: ❌ NO

33. **video_snapped** - Snap su video
    - Destinatario: Creatore video
    - Trigger: Utente crea snap
    - Dati: video_id, snap_id, snapper_id
    - Azione: "Vedi Snap" → /videos/{id}#snap-{snap_id}
    - Priorità: NORMAL
    - Email: ❌ NO

---

### 🛡️ **MODERAZIONE** (3 tipi)

34. **content_reported** - Contenuto segnalato
    - Destinatario: Moderatori
    - Trigger: Utente segnala contenuto
    - Dati: content_type, content_id, report_id, reason
    - Azione: "Revisiona" → /moderation/reports/{id}
    - Priorità: HIGH
    - Email: ✅ SI (moderatori)

35. **moderation_response** - Risposta moderazione
    - Destinatario: Utente che ha segnalato
    - Trigger: Moderatore completa revisione
    - Dati: report_id, action_taken, notes
    - Azione: "Vedi Risposta" → /moderation/reports/{id}
    - Priorità: NORMAL
    - Email: ✅ SI

36. **moderation_update** - Aggiornamento contenuto moderato
    - Destinatario: Creatore contenuto
    - Trigger: Moderatore approva/rifiuta contenuto
    - Dati: content_type, content_id, status, notes
    - Azione: "Vedi Contenuto" → dynamic
    - Priorità: HIGH
    - Email: ✅ SI

---

### 💬 **CHAT** (1 tipo)

37. **chat_message** - Nuovo messaggio chat
    - Destinatario: Partecipante conversazione
    - Trigger: Utente invia messaggio
    - Dati: chat_room_id, sender_id, message_id, unread_count
    - Azione: "Apri Chat" → /chat?room={id}
    - Priorità: NORMAL
    - Email: ❌ NO (real-time)
    - Note: Si raggruppa per chat_room_id

---

### 📅 **DISPONIBILITÀ** (2 tipi)

38. **availability_request** - Richiesta disponibilità
    - Destinatario: Utente invitato
    - Trigger: Organizzatore richiede disponibilità
    - Dati: event_id, availability_id
    - Azione: "Rispondi" → /events/{id}/availability
    - Priorità: NORMAL
    - Email: ✅ SI

39. **availability_response** - Risposta disponibilità
    - Destinatario: Organizzatore evento
    - Trigger: Utente risponde a disponibilità
    - Dati: event_id, user_id, selected_dates
    - Azione: "Vedi Risposte" → /events/{id}/availability
    - Priorità: NORMAL
    - Email: ❌ NO

---

### 🌍 **TRADUZIONI** (6 tipi)

40. **translation_proposal** - Proposta traduzione
    - Destinatario: Creatore contenuto originale
    - Trigger: Utente propone traduzione
    - Dati: content_type, content_id, translation_id, language
    - Azione: "Revisiona Traduzione" → dynamic
    - Priorità: NORMAL
    - Email: ✅ SI

41. **translation_accepted** - Traduzione accettata
    - Destinatario: Traduttore
    - Trigger: Autore accetta traduzione
    - Dati: content_type, content_id, translation_id
    - Azione: "Vedi Traduzione" → dynamic
    - Priorità: NORMAL
    - Email: ✅ SI

42. **translation_rejected** - Traduzione rifiutata
    - Destinatario: Traduttore
    - Trigger: Autore rifiuta traduzione
    - Dati: content_type, content_id, translation_id, reason
    - Azione: "Vedi Traduzione" → dynamic
    - Priorità: NORMAL
    - Email: ❌ NO

43. **translation_counter** - Controproposta traduzione
    - Destinatario: Traduttore originale
    - Trigger: Autore propone modifiche
    - Dati: content_type, content_id, translation_id, counter_proposal
    - Azione: "Vedi Controproposta" → dynamic
    - Priorità: NORMAL
    - Email: ✅ SI

44. **translation_message** - Messaggio su traduzione
    - Destinatario: Entrambe le parti
    - Trigger: Messaggio nella discussione traduzione
    - Dati: translation_id, message
    - Azione: "Vedi Discussione" → dynamic
    - Priorità: LOW
    - Email: ❌ NO

45. **translation_submitted** - Traduzione inviata
    - Destinatario: Autore originale
    - Trigger: Traduttore invia versione finale
    - Dati: translation_id
    - Azione: "Approva Traduzione" → dynamic
    - Priorità: NORMAL
    - Email: ✅ SI

46. **translation_approved** - Traduzione approvata
    - Destinatario: Traduttore
    - Trigger: Autore approva versione finale
    - Dati: translation_id
    - Azione: "Vedi Traduzione Pubblicata" → dynamic
    - Priorità: NORMAL
    - Email: ✅ SI

---

## 📌 **NOTIFICHE MANCANTI DA AGGIUNGERE**

### 🆕 **NUOVE NOTIFICHE NECESSARIE**

#### Eventi - Audience
47. **event_audience_invitation** - Invito come pubblico
    - Destinatario: Utente invitato
    - Trigger: Organizzatore invita ad assistere
    - Dati: event_id, invitation_id
    - Azione: "Vedi Evento" → /events/{id}
    - Priorità: NORMAL
    - Email: ✅ SI

#### Social - Follow
48. **user_followed** - Nuovo follower
    - Destinatario: Utente seguito
    - Trigger: Utente segue
    - Dati: follower_id, follower_name
    - Azione: "Vedi Profilo" → /user/{follower_id}
    - Priorità: LOW
    - Email: ❌ NO

#### Contenuti - Menzioni
49. **user_mentioned** - Menzione in contenuto/commento
    - Destinatario: Utente menzionato
    - Trigger: @username in testo
    - Dati: content_type, content_id, mentioner_id
    - Azione: "Vedi" → dynamic
    - Priorità: NORMAL
    - Email: ✅ SI (se preferenze)

#### Badge/Gamification
50. **badge_earned** - Nuovo badge ottenuto
    - Destinatario: Utente
    - Trigger: Sistema assegna badge
    - Dati: badge_id, badge_name
    - Azione: "Vedi Badge" → /profile/badges
    - Priorità: NORMAL
    - Email: ❌ NO

51. **level_up** - Livello aumentato
    - Destinatario: Utente
    - Trigger: Raggiunge nuovo livello
    - Dati: old_level, new_level
    - Azione: "Vedi Profilo" → /profile
    - Priorità: LOW
    - Email: ❌ NO

#### Sistema
52. **system_announcement** - Annuncio di sistema
    - Destinatario: Tutti gli utenti (o segmento)
    - Trigger: Admin pubblica annuncio
    - Dati: announcement_id, category
    - Azione: "Leggi" → /announcements/{id}
    - Priorità: NORMAL o HIGH
    - Email: ✅ SI (se urgente)

---

## 📊 RIEPILOGO STATISTICHE

**Totale tipi notifiche**: 52

### Per Categoria:
- 🎪 Eventi: 10 (19%)
- 👥 Gruppi: 9 (17%)
- 💼 Gigs: 7 (13%)
- ❤️ Social: 4 (8%)
- 🛡️ Moderazione: 3 (6%)
- 💬 Chat: 1 (2%)
- 📅 Disponibilità: 2 (4%)
- 🌍 Traduzioni: 6 (12%)
- 🆕 Nuove proposte: 6 (12%)
- 🏆 Gamification: 2 (4%)
- 📢 Sistema: 1 (2%)

### Per Priorità:
- 🔴 URGENT: 1 (2%)
- 🟠 HIGH: 11 (21%)
- 🟡 NORMAL: 30 (58%)
- 🟢 LOW: 10 (19%)

### Email:
- ✅ Con email: 24 (46%)
- ❌ Senza email: 28 (54%)

---

## 🎨 DESIGN NOTIFICHE

### Elementi UI per ogni notifica:
1. **Icona** - Specifica per tipo
2. **Titolo** - Breve e chiaro
3. **Messaggio** - Dettaglio azione
4. **Timestamp** - Relativo (2h fa, 1 giorno fa)
5. **Avatar** - Utente che ha generato notifica
6. **Badge** - Priorità (se HIGH/URGENT)
7. **Azione primaria** - Bottone CTA
8. **Azioni secondarie** - (Segna letto, elimina)
9. **Stato** - Letto/Non letto

### Raggruppamento:
- Chat: Per chat_room_id
- Social: Per content (max 5 like, poi "e altri X")
- Eventi: Non raggruppare

---

## 🔔 CANALI NOTIFICA

1. **In-App** (header dropdown)
   - Tutte le notifiche
   - Real-time con Livewire polling o broadcasting
   - Badge counter non lette

2. **Email**
   - Solo notifiche importanti
   - Rispetta preferenze utente
   - Template HTML responsive

3. **Real-time (futuro)**
   - WebSocket/Pusher
   - Toast notifications
   - Sound (opzionale)

---

## ⚙️ PREFERENZE UTENTE

Ogni utente può configurare:
- Email ON/OFF per categoria
- Raggruppamento ON/OFF
- Frequenza digest (real-time, daily, weekly)
- Suoni ON/OFF
- Desktop notifications ON/OFF

---

## 🚀 PROSSIMI PASSI

1. ✅ Analisi completata
2. ⏳ Creare migration per nuovi tipi
3. ⏳ Implementare notifiche mancanti
4. ⏳ Creare componenti Livewire per UI
5. ⏳ Template email
6. ⏳ Preferenze utente
7. ⏳ Testing

---

**Vuoi che proceda con l'implementazione? Da dove vuoi partire?**

