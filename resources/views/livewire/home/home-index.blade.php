<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Home Page Livewire</h5>
                    </div>
                    <div class="card-body">
                        <p>Home page migrata a Livewire!</p>
                        
                        <!-- Test Carousel -->
                        @if ($carousels && $carousels->count() > 0)
                            <div class="alert alert-success">
                                <strong>Carousel:</strong> {{ $carousels->count() }} elementi trovati
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <strong>Carousel:</strong> Nessun elemento trovato
                            </div>
                        @endif

                        <!-- Test Videos -->
                        @if ($recentVideos && $recentVideos->count() > 0)
                            <div class="alert alert-success">
                                <strong>Video Recenti:</strong> {{ $recentVideos->count() }} elementi trovati
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <strong>Video Recenti:</strong> Nessun elemento trovato
                            </div>
                        @endif

                        <!-- Test Events -->
                        @if ($recentEvents && $recentEvents->count() > 0)
                            <div class="alert alert-success">
                                <strong>Eventi Recenti:</strong> {{ $recentEvents->count() }} elementi trovati
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <strong>Eventi Recenti:</strong> Nessun elemento trovato
                            </div>
                        @endif

                        <!-- Test Stats -->
                        <div class="alert alert-info">
                            <strong>Statistiche:</strong>
                            <ul class="mb-0">
                                <li>Video: {{ $stats['total_videos'] ?? 0 }}</li>
                                <li>Eventi: {{ $stats['total_events'] ?? 0 }}</li>
                                <li>Utenti: {{ $stats['total_users'] ?? 0 }}</li>
                                <li>Views: {{ $stats['total_views'] ?? 0 }}</li>
                            </ul>
                        </div>

                        <!-- Test Toggle -->
                        <div class="alert alert-primary">
                            <strong>Toggle Test:</strong>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       wire:click="togglePoetryContent('{{ $poetryContentType === 'new' ? 'popular' : 'new' }}')"
                                       {{ $poetryContentType === 'popular' ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    Poetry Content: {{ $poetryContentType }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>