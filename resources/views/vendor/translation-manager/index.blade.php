@extends('layout.master')

@section('title', 'Translation Manager')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="ph-duotone ph-translate me-2"></i>
                    Translation Manager
                </h4>
                <p class="text-muted mb-0">Gestisci traduzioni multi-lingua</p>
            </div>
        </div>
    </div>

    <!-- Alert Success -->
    <div class="alert alert-success success-import" style="display:none;">
        <i class="ph-duotone ph-check-circle me-2"></i>
        Importazione completata! Processati <strong class="counter">N</strong> elementi.
    </div>
    
    <div class="alert alert-success success-find" style="display:none;">
        <i class="ph-duotone ph-magnifying-glass me-2"></i>
        Ricerca completata! Trovati <strong class="counter">N</strong> elementi.
    </div>
    
    <div class="alert alert-success success-publish" style="display:none;">
        <i class="ph-duotone ph-check-circle me-2"></i>
        Traduzioni del gruppo '{{ $group ?? '' }}' pubblicate!
    </div>
    
    <div class="alert alert-success success-publish-all" style="display:none;">
        <i class="ph-duotone ph-check-circle me-2"></i>
        Tutte le traduzioni sono state pubblicate!
    </div>

    <!-- Actions Card -->
    @if(!isset($group))
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-duotone ph-upload me-2"></i>
                        Importa Traduzioni
                    </h5>
                </div>
                <div class="card-body">
                    <form class="form-import" method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postImport') }}" data-remote="true">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Modalità importazione</label>
                            <select name="replace" class="form-select">
                                <option value="0">Aggiungi nuove traduzioni</option>
                                <option value="1">Sostituisci traduzioni esistenti</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" data-disable-with="Caricamento..">
                            <i class="ph-duotone ph-upload me-2"></i>
                            Importa gruppi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-duotone ph-magnifying-glass me-2"></i>
                        Scansiona File
                    </h5>
                </div>
                <div class="card-body">
                    <form class="form-find" method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postFind') }}" data-remote="true" data-confirm="Sei sicuro di voler scansionare i file dell'applicazione? Tutte le chiavi trovate verranno aggiunte al database.">
                        @csrf
                        <p class="text-muted mb-3">Scansiona i file per trovare tutte le chiavi di traduzione usate nel codice.</p>
                        <button type="submit" class="btn btn-info" data-disable-with="Ricerca in corso..">
                            <i class="ph-duotone ph-magnifying-glass me-2"></i>
                            Trova traduzioni nei file
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Group Selection Card -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ph-duotone ph-folder me-2"></i>
                Seleziona Gruppo
            </h5>
        </div>
        <div class="card-body">
            @if(isset($group))
            <form class="form-inline form-publish mb-4" method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postPublish', $group) }}" data-remote="true" data-confirm="Sei sicuro di voler pubblicare le traduzioni del gruppo '{{ $group }}'? Questo sovrascriverà i file di lingua esistenti.">
                @csrf
                <button type="submit" class="btn btn-primary me-2" data-disable-with="Pubblicazione..">
                    <i class="ph-duotone ph-upload me-2"></i>
                    Pubblica traduzioni
                </button>
                <a href="{{ action('\Barryvdh\TranslationManager\Controller@getIndex') }}" class="btn btn-secondary">
                    <i class="ph-duotone ph-arrow-left me-2"></i>
                    Indietro
                </a>
            </form>
            @endif

            <form role="form" method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postAddGroup') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Scegli un gruppo da modificare</label>
                    <select name="group" id="group" class="form-select group-select">
                        <option value="">-- Seleziona gruppo --</option>
                        @foreach($groups as $key => $value)
                            <option value="{{ $key }}" {{ $key == $group ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Oppure crea un nuovo gruppo</label>
                    <input type="text" class="form-control" name="new-group" placeholder="Nome nuovo gruppo (es. messages)" />
                </div>

                <button type="submit" class="btn btn-success" name="add-group">
                    <i class="ph-duotone ph-plus me-2"></i>
                    Aggiungi e modifica chiavi
                </button>
            </form>
        </div>
    </div>

    <!-- Translations Table -->
    @if($group)
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ph-duotone ph-text-aa me-2"></i>
                Traduzioni: {{ $group }}
                <span class="badge bg-primary ms-2">{{ $numTranslations }} totali</span>
                <span class="badge bg-warning ms-2">{{ $numChanged }} modificate</span>
            </h5>
        </div>
        <div class="card-body">
            <!-- Add New Keys -->
            <form action="{{ action('\Barryvdh\TranslationManager\Controller@postAdd', array($group)) }}" method="POST" class="mb-4">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Aggiungi nuove chiavi a questo gruppo</label>
                    <textarea class="form-control" rows="3" name="keys" placeholder="Aggiungi 1 chiave per riga, senza il prefisso del gruppo"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="ph-duotone ph-plus me-2"></i>
                    Aggiungi chiavi
                </button>
            </form>

            <!-- Translations Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th style="width: 20%">Chiave</th>
                            @foreach ($locales as $locale)
                                <th>{{ strtoupper($locale) }}</th>
                            @endforeach
                            @if ($deleteEnabled)
                                <th style="width: 50px"></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($translations as $key => $translation)
                            <tr id="{{ $key }}">
                                <td class="fw-bold">{{ $key }}</td>
                                @foreach ($locales as $locale)
                                    @php $t = isset($translation[$locale]) ? $translation[$locale] : null @endphp
                                    <td>
                                        <a href="#edit"
                                           class="editable status-{{ $t ? (int) $t->status : 0 }} locale-{{ $locale }}"
                                           data-locale="{{ $locale }}"
                                           data-name="{{ $locale }}|{{ $key }}"
                                           data-type="textarea"
                                           data-pk="{{ $t ? (int) $t->id : 0 }}"
                                           data-url="{{ $editUrl }}"
                                           data-title="Inserisci traduzione">{{ $t ? $t->value : '' }}</a>
                                    </td>
                                @endforeach
                                @if ($deleteEnabled)
                                    <td>
                                        <a href="{{ action('\Barryvdh\TranslationManager\Controller@postDelete', [$group, $key]) }}"
                                           class="delete-key btn btn-sm btn-danger"
                                           data-confirm="Sei sicuro di voler eliminare le traduzioni per '{{ $key }}'?"
                                           title="Elimina">
                                            <i class="ph-duotone ph-trash"></i>
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <!-- Manage Locales -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ph-duotone ph-globe me-2"></i>
                Gestisci Lingue
            </h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Lingue attualmente supportate:</p>
            
            <form class="form-remove-locale" method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postRemoveLocale') }}" data-confirm="Sei sicuro di voler rimuovere questa lingua e tutti i suoi dati?">
                @csrf
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @foreach($locales as $locale)
                        <div class="badge bg-light-primary p-2">
                            <button type="submit" name="remove-locale[{{ $locale }}]" class="btn btn-sm btn-danger me-2" style="padding: 0.1rem 0.3rem;" data-disable-with="...">
                                &times;
                            </button>
                            <span class="fw-bold">{{ strtoupper($locale) }}</span>
                        </div>
                    @endforeach
                </div>
            </form>

            <form class="form-add-locale" method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postAddLocale') }}">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="new-locale" class="form-control" placeholder="es, fr, de..." />
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success" data-disable-with="Aggiunta..">
                            <i class="ph-duotone ph-plus me-2"></i>
                            Aggiungi nuova lingua
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Export All -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ph-duotone ph-download me-2"></i>
                Esporta Tutto
            </h5>
        </div>
        <div class="card-body">
            <form class="form-inline form-publish-all" method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postPublish', '*') }}" data-remote="true" data-confirm="Sei sicuro di voler pubblicare TUTTE le traduzioni? Questo sovrascriverà i file di lingua esistenti.">
                @csrf
                <button type="submit" class="btn btn-primary" data-disable-with="Pubblicazione..">
                    <i class="ph-duotone ph-download me-2"></i>
                    Pubblica tutte le traduzioni
                </button>
            </form>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    .editable {
        cursor: pointer;
        border-bottom: 1px dashed #999;
        padding: 4px 8px;
        display: block;
        min-height: 30px;
        position: relative;
    }
    .editable:hover {
        background-color: #f8f9fa;
        border-bottom: 2px dashed #0d6efd;
    }
    .editable.status-1 {
        font-weight: bold;
        border-bottom-color: #198754;
    }
    .editable-empty {
        color: #999;
        font-style: italic;
    }
    .editable:hover::after {
        content: '✏️';
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        color: #0d6efd;
        font-size: 12px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
<script>
jQuery(document).ready(function($){
    $.ajaxSetup({
        beforeSend: function(xhr, settings) {
            settings.data += "&_token={{ csrf_token() }}";
        }
    });

    // Custom inline editing con Bootstrap 5
    $('.editable').on('click', function(e){
        e.preventDefault();
        var $el = $(this);
        var value = $el.text();
        var url = $el.data('url');
        var name = $el.data('name');
        
        // Crea un input inline
        var $input = $('<textarea class="form-control form-control-sm" rows="2"></textarea>').val(value);
        var $saveBtn = $('<button class="btn btn-success btn-sm mt-1 me-1"><i class="ph-duotone ph-check"></i></button>');
        var $cancelBtn = $('<button class="btn btn-secondary btn-sm mt-1"><i class="ph-duotone ph-x"></i></button>');
        var $btnGroup = $('<div class="mt-1"></div>').append($saveBtn).append($cancelBtn);
        
        $el.hide().after($input).after($btnGroup);
        $input.focus().select();
        
        // Save
        $saveBtn.on('click', function(){
            var newValue = $input.val();
            $.post(url, {
                name: name,
                value: newValue,
                _token: '{{ csrf_token() }}'
            }, function(response){
                $el.text(newValue).removeClass('status-0').addClass('status-1').show();
                $input.remove();
                $btnGroup.remove();
            }).fail(function(){
                alert('Errore nel salvataggio');
            });
        });
        
        // Cancel
        $cancelBtn.on('click', function(){
            $el.show();
            $input.remove();
            $btnGroup.remove();
        });
        
        // Enter to save, Esc to cancel
        $input.on('keydown', function(e){
            if(e.key === 'Enter' && e.ctrlKey){
                $saveBtn.click();
            } else if(e.key === 'Escape'){
                $cancelBtn.click();
            }
        });
    });

    $('.group-select').on('change', function(){
        var group = $(this).val();
        if (group) {
            window.location.href = '{{ action('\Barryvdh\TranslationManager\Controller@getView') }}/'+$(this).val();
        } else {
            window.location.href = '{{ action('\Barryvdh\TranslationManager\Controller@getIndex') }}';
        }
    });

    $("a.delete-key").on('confirm:complete',function(event,result){
        if(result) {
            var row = $(this).closest('tr');
            var url = $(this).attr('href');
            var id = row.attr('id');
            $.post( url, {id: id}, function(){
                row.fadeOut(function(){ $(this).remove(); });
            });
        }
        return false;
    });

    $('.form-import').on('ajax:success', function (e, data) {
        $('div.success-import strong.counter').text(data.counter);
        $('div.success-import').slideDown();
        setTimeout(function(){ window.location.reload(); }, 2000);
    });

    $('.form-find').on('ajax:success', function (e, data) {
        $('div.success-find strong.counter').text(data.counter);
        $('div.success-find').slideDown();
        setTimeout(function(){ window.location.reload(); }, 2000);
    });

    $('.form-publish').on('ajax:success', function (e, data) {
        $('div.success-publish').slideDown();
    });

    $('.form-publish-all').on('ajax:success', function (e, data) {
        $('div.success-publish-all').slideDown();
    });
});
</script>
@endpush
@endsection

