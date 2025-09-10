@extends('layout.master')

@section('title', 'Gestione Posizioni Ingaggi')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="page-title-box">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                <div class="page-title-right">
                    
                </div>
            </div>
        </div>

        <!-- Header con azioni -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Gestione Posizioni Ingaggi</h4>
                    <a href="{{ route('admin.gig-positions.create') }}" class="btn btn-primary">
                        <i class="ph ph-plus me-2"></i>Nuova Posizione
                    </a>
                </div>
            </div>
        </div>

        <!-- Lista posizioni -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Chiave</th>
                                        <th>Descrizione</th>
                                        <th>Stato</th>
                                        <th>Ordine</th>
                                        <th>Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($positions as $position)
                                        <tr>
                                            <td>
                                                <strong>{{ $position->name }}</strong>
                                            </td>
                                            <td>
                                                <code>{{ $position->key }}</code>
                                            </td>
                                            <td>
                                                @if($position->description)
                                                    {{ Str::limit($position->description, 50) }}
                                                @else
                                                    <span class="text-muted">Nessuna descrizione</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($position->is_active)
                                                    <span class="badge bg-success">Attiva</span>
                                                @else
                                                    <span class="badge bg-secondary">Inattiva</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $position->sort_order }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.gig-positions.edit', $position) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="Modifica">
                                                        <i class="ph ph-pencil"></i>
                                                    </a>

                                                    <form action="{{ route('admin.gig-positions.toggle-status', $position) }}"
                                                          method="POST"
                                                          class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-warning"
                                                                title="{{ $position->is_active ? 'Disattiva' : 'Attiva' }}">
                                                            <i class="ph ph-{{ $position->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('admin.gig-positions.destroy', $position) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Sei sicuro di voler eliminare questa posizione?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-danger"
                                                                title="Elimina">
                                                            <i class="ph ph-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ph ph-briefcase f-s-48 mb-3"></i>
                                                    <p>Nessuna posizione di ingaggio trovata</p>
                                                    <a href="{{ route('admin.gig-positions.create') }}" class="btn btn-primary">
                                                        Crea la prima posizione
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
