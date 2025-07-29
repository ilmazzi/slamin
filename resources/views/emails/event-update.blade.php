@component('mail::message')

@if($updateType === 'cancelled')
# 🚫 {{ __('invitations.event') }} cancellato

Ciao **{{ $user->getDisplayName() }}**,

ci dispiace informarti che l'evento **{{ $event->title }}** è stato cancellato.

@component('mail::panel')
**{{ $event->title }}**
📅 Era previsto per {{ $eventDate }} alle {{ $eventTime }}
📍 {{ $venueInfo }}
@endcomponent

@elseif($updateType === 'reminder')
# ⏰ Promemoria evento

Ciao **{{ $user->getDisplayName() }}**!

Ti ricordiamo che l'evento **{{ $event->title }}** è previsto
@if($daysUntilEvent == 0)
**oggi**! 🎉
@elseif($daysUntilEvent == 1)
**domani**!
@else
tra **{{ $daysUntilEvent }} giorni**!
@endif

@component('mail::panel')
**{{ $event->title }}**
📅 {{ $eventDate }} alle {{ $eventTime }}
📍 {{ $venueInfo }}
@endcomponent

@else
# 📢 Aggiornamento evento

Ciao **{{ $user->getDisplayName() }}**!

{{ $organizerName }} ha aggiornato l'evento **{{ $event->title }}**.

@component('mail::panel')
**{{ $event->title }}**
📅 {{ $eventDate }} alle {{ $eventTime }}
📍 {{ $venueInfo }}
@endcomponent

@endif

@if($customMessage)
## 💌 Messaggio dall'organizzatore

*"{{ $customMessage }}"*
@endif

@if($changes && count($changes) > 0)
## 🔄 Cosa è cambiato

@foreach($changes as $field => $change)
@if($field === 'start_datetime')
- **📅 Data/Ora:** {{ $change['old'] }} → **{{ $change['new'] }}**
@elseif($field === 'venue_name')
- **📍 Venue:** {{ $change['old'] }} → **{{ $change['new'] }}**
@elseif($field === 'venue_address')
- **🏠 Indirizzo:** {{ $change['old'] }} → **{{ $change['new'] }}**
@elseif($field === 'description')
- **📝 Descrizione aggiornata**
@elseif($field === 'requirements')
- **📋 Nuovi requisiti aggiunti**
@else
- **{{ ucfirst($field) }}:** aggiornato
@endif
@endforeach
@endif

@if($updateType !== 'cancelled')
## 📍 Informazioni venue

**{{ $event->venue_name }}**
{{ $event->venue_address }}
{{ $event->city }}, {{ $event->country }}

@if($mapUrl)
@component('mail::button', ['url' => $mapUrl, 'color' => 'primary'])
📱 Apri in Google Maps
@endcomponent
@endif

### ⏰ Dettagli evento

- **Durata:** {{ $event->duration }} ore
@if($event->entry_fee > 0)
- **Costo:** €{{ number_format($event->entry_fee, 2) }}
@else
- **{{ __('common.free') }}** 🎉
@endif
@if($event->max_participants)
- **Posti limitati:** {{ $event->max_participants }} partecipanti
@endif

@component('mail::button', ['url' => $eventUrl])
📖 Vedi tutti i dettagli
@endcomponent

@if($updateType === 'reminder')
---

### 🎯 Preparati per l'evento!

@if($event->requirements)
**Requisiti da ricordare:**
{{ $event->requirements }}
@endif

**Suggerimenti:**
- Arriva almeno 15 minuti prima
- Porta un documento di identità
- Controlla gli aggiornamenti dell'ultimo minuto
- Preparati a vivere un'esperienza incredibile! 🌟

@endif
@endif

---

@if($updateType === 'cancelled')
Ci scusiamo per l'inconveniente. Se hai domande, non esitare a contattare {{ $organizerName }}.

**Il team Poetry Slam** 💔
@else
Non vediamo l'ora di vederti! 🎤

**Il team Poetry Slam** ✨
@endif

@component('mail::subcopy')
Se hai domande sull'evento, rispondi a questa email o visita la pagina dell'evento:
{{ $eventUrl }}
@endcomponent

@endcomponent
