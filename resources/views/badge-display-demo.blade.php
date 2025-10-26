@extends('layout.master')

@section('title', 'Badge Display Styles - Demo')

@section('main-content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-2">🎨 Badge Display Styles - Scegli il tuo preferito!</h2>
            <p class="text-muted">Clicca su ogni sezione per vedere le animazioni e interazioni</p>
        </div>
    </div>

    <!-- Style 1: Floating Bubbles -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0">
                        <i class="ph ph-circles-three me-2"></i>
                        Style 1: Floating Bubbles 🫧
                    </h4>
                    <small>Delicato, fluido, artistico - Perfetto per ambiente poetico</small>
                </div>
                <div class="card-body">
                    @livewire('profile.badge-display-floating-bubbles', ['user' => auth()->user()])
                    
                    <div class="mt-3 p-3 bg-light rounded">
                        <strong>✨ Caratteristiche:</strong>
                        <ul class="mb-0 f-s-14">
                            <li>Badge fluttuano dolcemente come bolle</li>
                            <li>Movimento continuo su/giù con breathing effect</li>
                            <li>Hover fa crescere la bolla con glow</li>
                            <li>Click apre modal con dettagli completi</li>
                            <li>Dimensioni e posizioni random per naturalezza</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Style 2: Carousel 3D -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-success text-white">
                    <h4 class="mb-0">
                        <i class="ph ph-slideshow me-2"></i>
                        Style 2: Carousel 3D 🎡
                    </h4>
                    <small>Dinamico, moderno, interattivo - Massimo wow factor</small>
                </div>
                <div class="card-body">
                    @livewire('profile.badge-display-carousel3d', ['user' => auth()->user()])
                    
                    <div class="mt-3 p-3 bg-light rounded">
                        <strong>✨ Caratteristiche:</strong>
                        <ul class="mb-0 f-s-14">
                            <li>Effetto 3D "coverflow" con prospettiva</li>
                            <li>Badge centrale grande, laterali piccoli e ruotati</li>
                            <li>Frecce e dots per navigazione</li>
                            <li>Animazione fluida con easing avanzato</li>
                            <li>Shine effect e icon float animation</li>
                            <li>Counter badge visibile</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Style 3: Flip Cards -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-warning text-white">
                    <h4 class="mb-0">
                        <i class="ph ph-cards me-2"></i>
                        Style 3: Flip Cards 🎴
                    </h4>
                    <small>Elegante, informativo, professionale - Perfetto equilibrio</small>
                </div>
                <div class="card-body">
                    @livewire('profile.badge-display-flip-cards', ['user' => auth()->user()])
                    
                    <div class="mt-3 p-3 bg-light rounded">
                        <strong>✨ Caratteristiche:</strong>
                        <ul class="mb-0 f-s-14">
                            <li>Griglia responsive di carte eleganti</li>
                            <li>Fronte: icona badge pulita</li>
                            <li>Retro: dettagli completi (nome, descrizione, stats)</li>
                            <li>Auto-flip random ogni 5 secondi (opzionale)</li>
                            <li>Click per flip manuale</li>
                            <li>Stagger animation all'ingresso</li>
                            <li>Hover scale e rotation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Style 4: Stack Cards -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-danger text-white">
                    <h4 class="mb-0">
                        <i class="ph ph-stack me-2"></i>
                        Style 4: Stack Cards 📚
                    </h4>
                    <small>Come Tinder - swipe per sfogliare i badge</small>
                </div>
                <div class="card-body">
                    @livewire('profile.badge-display-stack-cards', ['user' => auth()->user()])
                    
                    <div class="mt-3 p-3 bg-light rounded">
                        <strong>✨ Caratteristiche:</strong>
                        <ul class="mb-0 f-s-14">
                            <li>Badge impilati come carte</li>
                            <li>Solo il primo completamente visibile</li>
                            <li>Gli altri fanno "peek" da dietro</li>
                            <li>Navigazione con frecce o swipe</li>
                            <li>Progress bar in basso</li>
                            <li>Focus su un badge alla volta</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Style 5: Badge Wall Grid -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-info text-white">
                    <h4 class="mb-0">
                        <i class="ph ph-grid-four me-2"></i>
                        Style 5: Badge Wall (Trophy Case) 🏆
                    </h4>
                    <small>Mostra earned E locked badges - motivazione a sbloccare</small>
                </div>
                <div class="card-body">
                    @livewire('profile.badge-display-wall-grid', ['user' => auth()->user()])
                    
                    <div class="mt-3 p-3 bg-light rounded">
                        <strong>✨ Caratteristiche:</strong>
                        <ul class="mb-0 f-s-14">
                            <li>Badge guadagnati con glow e checkmark</li>
                            <li>Badge bloccati in grayscale con lucchetto</li>
                            <li>Progress ring mostra requirement</li>
                            <li>Click per dettagli completi</li>
                            <li>Stagger animation all'ingresso</li>
                            <li>Shine effect sui badge earned</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Style 6: Medal Podium -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-warning text-white">
                    <h4 class="mb-0">
                        <i class="ph ph-trophy me-2"></i>
                        Style 6: Medal Podium 🥇
                    </h4>
                    <small>Top 3 badges sul podio - spotlight e corona</small>
                </div>
                <div class="card-body">
                    @livewire('profile.badge-display-podium', ['user' => auth()->user()])
                    
                    <div class="mt-3 p-3 bg-light rounded">
                        <strong>✨ Caratteristiche:</strong>
                        <ul class="mb-0 f-s-14">
                            <li>Top 3 badge sul podio (oro, argento, bronzo)</li>
                            <li>Spotlight animati per ogni posizione</li>
                            <li>Corona sul 1° posto</li>
                            <li>Auto-rotazione ogni 5s (opzionale)</li>
                            <li>Altri badge in galleria sotto</li>
                            <li>Sfondo scuro drammatico</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Style 7: Constellation Map -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-dark text-white">
                    <h4 class="mb-0">
                        <i class="ph ph-planet me-2"></i>
                        Style 7: Constellation Map ⭐
                    </h4>
                    <small>Badge come stelle in una costellazione - super spettacolare!</small>
                </div>
                <div class="card-body">
                    @livewire('profile.badge-display-constellation', ['user' => auth()->user()])
                    
                    <div class="mt-3 p-3 bg-light rounded">
                        <strong>✨ Caratteristiche:</strong>
                        <ul class="mb-0 f-s-14">
                            <li>Badge disposti in pattern circolare (costellazione)</li>
                            <li>Linee connettono badge della stessa categoria</li>
                            <li>Badge earned brillano con particles</li>
                            <li>Badge locked in grayscale con lucchetto</li>
                            <li>100 stelle background che twinklano</li>
                            <li>Click per modal dettagli spaziale</li>
                            <li>Effetto "discover" quando unlock</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comparison Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">📊 Confronto TUTTI gli Stili</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Caratteristica</th>
                                    <th>🫧 Floating Bubbles</th>
                                    <th>🎡 Carousel 3D</th>
                                    <th>🎴 Flip Cards</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Complessità</strong></td>
                                    <td>⭐⭐ Semplice</td>
                                    <td>⭐⭐⭐⭐ Complessa</td>
                                    <td>⭐⭐⭐ Media</td>
                                </tr>
                                <tr>
                                    <td><strong>Wow Factor</strong></td>
                                    <td>⭐⭐⭐ Alto</td>
                                    <td>⭐⭐⭐⭐⭐ Massimo</td>
                                    <td>⭐⭐⭐⭐ Molto Alto</td>
                                </tr>
                                <tr>
                                    <td><strong>Performance</strong></td>
                                    <td>⭐⭐⭐⭐ Ottima</td>
                                    <td>⭐⭐⭐ Buona</td>
                                    <td>⭐⭐⭐⭐⭐ Eccellente</td>
                                </tr>
                                <tr>
                                    <td><strong>Mobile Friendly</strong></td>
                                    <td>⭐⭐⭐ Buono</td>
                                    <td>⭐⭐ Medio</td>
                                    <td>⭐⭐⭐⭐⭐ Perfetto</td>
                                </tr>
                                <tr>
                                    <td><strong>Info Visualizzate</strong></td>
                                    <td>Modal separato</td>
                                    <td>Sempre visibili</td>
                                    <td>Flip per vedere</td>
                                </tr>
                                <tr>
                                    <td><strong>Stile</strong></td>
                                    <td>Artistico, poetico</td>
                                    <td>Tech, moderno</td>
                                    <td>Elegante, pulito</td>
                                </tr>
                                <tr>
                                    <td><strong>Migliore per</strong></td>
                                    <td>Pochi badge (max 10)</td>
                                    <td>Showcase principale</td>
                                    <td>Molti badge (griglia)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

