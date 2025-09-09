@extends('layout.master')

@section('title', 'Bacheca - ' . $group->name)

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">
                            <i class="ti ti-news me-2"></i>
                            Bacheca - {{ $group->name }}
                        </h1>
                        <p class="page-description">
                            Annunci e comunicazioni del gruppo
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('groups.show', $group) }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i>Torna al gruppo
                        </a>
                        <a href="{{ route('groups.announcements.create', $group) }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>Nuovo annuncio
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filtri -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex gap-2">
                                <a href="{{ request()->fullUrlWithQuery(['filter' => 'all']) }}" 
                                   class="btn btn-sm {{ request('filter') === 'all' || !request('filter') ? 'btn-primary' : 'btn-outline-primary' }}">
                                    Tutti
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['filter' => 'pinned']) }}" 
                                   class="btn btn-sm {{ request('filter') === 'pinned' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    <i class="ti ti-pin me-1"></i>Pinnati
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['filter' => 'polls']) }}" 
                                   class="btn btn-sm {{ request('filter') === 'polls' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    <i class="ti ti-chart-bar me-1"></i>Sondaggi
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" class="d-flex">
                                <input type="text" 
                                       name="search" 
                                       class="form-control form-control-sm me-2" 
                                       placeholder="Cerca annunci..." 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="ti ti-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista annunci -->
            <div class="announcements-list">
                @forelse($announcements as $announcement)
                    <x-group-announcement-card :announcement="$announcement" :group="$group" />
                @empty
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ti ti-news f-s-48 text-muted mb-3"></i>
                            <h5 class="text-muted">Nessun annuncio trovato</h5>
                            <p class="text-muted">
                                @if(request('search'))
                                    Nessun annuncio corrisponde alla tua ricerca.
                                @else
                                    Non ci sono ancora annunci in questo gruppo.
                                @endif
                            </p>
                            @if(!request('search'))
                                <a href="{{ route('groups.announcements.create', $group) }}" class="btn btn-success">
                                    <i class="ti ti-plus me-1"></i>Crea il primo annuncio
                                </a>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Paginazione -->
            @if($announcements->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
