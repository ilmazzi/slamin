@component('mail::message')
# 🎭 Invito Poetry Slam

Ciao **{{ $invitation->invitedUser->name }}**!

{{ $inviter->name }} ti ha invitato a partecipare come **{{ $role }}** all'evento:

@component('mail::panel')
# {{ $event->title }}

📅 **{{ $eventDate }}** alle **{{ $eventTime }}**
📍 **{{ $venueInfo }}**

{{ $event->description }}
@endcomponent

## 🎯 Dettagli del tuo ruolo

Come **{{ $role }}**, sarai parte fondamentale di questo evento Poetry Slam!

@if($invitation->message)
### 💌 Messaggio dall'organizzatore
*"{{ $invitation->message }}"*
@endif

@if($compensation && $compensation > 0)
### 💰 Compenso
€{{ number_format($compensation, 2) }}
@endif

@if($event->requirements)
### 📋 Requisiti
{{ $event->requirements }}
@endif

## 🚀 Accetta l'invito

@component('mail::button', ['url' => $acceptUrl, 'color' => 'success'])
✅ Accetto l'invito
@endcomponent

@component('mail::button', ['url' => $declineUrl, 'color' => 'error'])
❌ Rifiuto l'invito
@endcomponent

---

### 📍 Come arrivare

**{{ $event->venue_name }}**
{{ $event->venue_address }}
{{ $event->city }}, {{ $event->country }}

@if($mapUrl)
[📱 Apri in Google Maps]({{ $mapUrl }})
@endif

### ⏰ Informazioni aggiuntive

- **Durata:** {{ $event->duration }} ore
@if($event->entry_fee > 0)
- **Costo partecipazione:** €{{ number_format($event->entry_fee, 2) }}
@else
- **Evento gratuito** 🎉
@endif
@if($event->max_participants)
- **Posti limitati:** {{ $event->max_participants }} partecipanti
@endif

@if($expiresAt)
⚠️ **Attenzione:** Questo invito scade il {{ $expiresAt->format('d/m/Y H:i') }}
@endif

---

### 🔍 Vuoi saperne di più?

@component('mail::button', ['url' => $eventUrl])
📖 Vedi dettagli evento
@endcomponent

Hai domande? Rispondi direttamente a questa email per contattare {{ $inviter->name }}.

Grazie e a presto sul palco! 🎤

**Il team Poetry Slam** ✨

@component('mail::subcopy')
Se non riesci a cliccare i pulsanti, copia e incolla questo link nel tuo browser:
**Accetta:** {{ $acceptUrl }}
**Rifiuta:** {{ $declineUrl }}
@endcomponent
@endcomponent
