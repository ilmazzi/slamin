@extends('layout.master')

@section('title', 'Analytics Dashboard')
@section('css')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
.analytics-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
}

.metric-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: none;
}

.metric-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.metric-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: #667eea;
    margin: 0;
}

.metric-label {
    color: #6c757d;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 5px;
}

.metric-change {
    font-size: 0.8rem;
    margin-top: 10px;
}

.metric-change.positive {
    color: #28a745;
}

.metric-change.negative {
    color: #dc3545;
}

.chart-container {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.chart-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #495057;
}

.timeframe-selector {
    background: white;
    border-radius: 10px;
    padding: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 30px;
}

.timeframe-btn {
    border: none;
    background: transparent;
    padding: 8px 16px;
    border-radius: 5px;
    transition: all 0.3s ease;
    color: #6c757d;
}

.timeframe-btn.active {
    background: #667eea;
    color: white;
}

.timeframe-btn:hover {
    background: #f8f9fa;
}

.timeframe-btn.active:hover {
    background: #5a6fd8;
}

.top-events-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.top-events-list li {
    padding: 15px 0;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: between;
}

.top-events-list li:last-child {
    border-bottom: none;
}

.event-info {
    flex-grow: 1;
}

.event-title {
    font-weight: 600;
    color: #495057;
    margin-bottom: 5px;
}

.event-meta {
    font-size: 0.8rem;
    color: #6c757d;
}

.participant-count {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.insight-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}

.insight-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #667eea;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}

.real-time-indicator {
    display: inline-flex;
    align-items: center;
    background: #28a745;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.7rem;
    margin-left: 10px;
}

.real-time-dot {
    width: 6px;
    height: 6px;
    background: white;
    border-radius: 50%;
    margin-right: 5px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.export-controls {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
}

.export-btn {
    background: #667eea;
    color: white;
    border: none;
    border-radius: 50px;
    padding: 15px 25px;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
}

.export-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
}

.heatmap-container {
    height: 200px;
    background: #f8f9fa;
    border-radius: 10px;
    position: relative;
    overflow: hidden;
}

.progress-ring {
    width: 120px;
    height: 120px;
    margin: 0 auto;
}

.progress-ring-circle {
    transition: stroke-dasharray 0.35s;
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
}
</style>
@endsection

@section('breadcrumb-title')
<h3>Analytics Dashboard</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Dashboard</li>
<li class="breadcrumb-item active">Analytics</li>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Analytics Header -->
    <div class="analytics-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2">
                    <i class="ph ph-chart-line me-3"></i>Analytics Dashboard
                    <span class="real-time-indicator">
                        <span class="real-time-dot"></span>
                        Live
                    </span>
                </h2>
                <p class="mb-0 opacity-75">Analisi approfondita delle performance dei tuoi eventi Poetry Slam</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="d-flex justify-content-end align-items-center">
                    <button class="btn btn-outline-light me-2" onclick="refreshAnalytics()">
                        <i class="ph ph-arrow-clockwise me-2"></i>Aggiorna
                    </button>
                    <button class="btn btn-light" onclick="exportAnalytics()">
                        <i class="ph ph-download me-2"></i>Esporta
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeframe Selector -->
    <div class="timeframe-selector">
        <div class="d-flex justify-content-center">
            <button class="timeframe-btn {{ $timeframe === '7days' ? 'active' : '' }}" data-timeframe="7days">
                Ultimi 7 giorni
            </button>
            <button class="timeframe-btn {{ $timeframe === '30days' ? 'active' : '' }}" data-timeframe="30days">
                Ultimi 30 giorni
            </button>
            <button class="timeframe-btn {{ $timeframe === '90days' ? 'active' : '' }}" data-timeframe="90days">
                Ultimi 3 mesi
            </button>
            <button class="timeframe-btn {{ $timeframe === '1year' ? 'active' : '' }}" data-timeframe="1year">
                Ultimo anno
            </button>
        </div>
    </div>

    <!-- Overview Metrics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="metric-card text-center">
                <div class="metric-number">{{ $analytics['overview']['total_events'] }}</div>
                <div class="metric-label">Eventi Totali</div>
                <div class="metric-change {{ $analytics['overview']['growth_rate'] >= 0 ? 'positive' : 'negative' }}">
                    <i class="ph ph-trend-{{ $analytics['overview']['growth_rate'] >= 0 ? 'up' : 'down' }} me-1"></i>
                    {{ abs($analytics['overview']['growth_rate']) }}% rispetto al periodo precedente
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="metric-card text-center">
                <div class="metric-number">{{ $analytics['overview']['total_participants'] }}</div>
                <div class="metric-label">Partecipanti Totali</div>
                <div class="metric-change positive">
                    <i class="ph ph-users me-1"></i>
                    {{ $analytics['overview']['avg_participants_per_event'] }} media per evento
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="metric-card text-center">
                <div class="metric-number">{{ $analytics['overview']['invitation_response_rate'] }}%</div>
                <div class="metric-label">Tasso Risposta Inviti</div>
                <div class="metric-change positive">
                    <i class="ph ph-envelope-open me-1"></i>
                    {{ $analytics['overview']['total_invitations_sent'] }} inviti inviati
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="metric-card text-center">
                <div class="metric-number">{{ $analytics['overview']['repeat_participants'] }}</div>
                <div class="metric-label">Partecipanti Ricorrenti</div>
                <div class="metric-change positive">
                    <i class="ph ph-repeat me-1"></i>
                    Alto engagement
                </div>
            </div>
        </div>
    </div>

    <!-- Main Analytics Grid -->
    <div class="row">
        <!-- Left Column: Charts -->
        <div class="col-lg-8">

            <!-- Event Creation Trend -->
            <div class="chart-container">
                <h5 class="chart-title">
                    <i class="ph ph-chart-line me-2"></i>Trend Creazione Eventi
                </h5>
                <canvas id="eventCreationChart" height="100"></canvas>
            </div>

            <!-- Revenue Analysis -->
            @if($analytics['events']['total_revenue'] > 0)
            <div class="chart-container">
                <h5 class="chart-title">
                    <i class="ph ph-currency-eur me-2"></i>Analisi Ricavi
                </h5>
                <div class="row">
                    <div class="col-md-8">
                        <canvas id="revenueChart" height="120"></canvas>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="progress-ring">
                                <svg class="progress-ring-svg" width="120" height="120">
                                    <circle
                                        class="progress-ring-circle"
                                        stroke="#e9ecef"
                                        stroke-width="10"
                                        fill="transparent"
                                        r="50"
                                        cx="60"
                                        cy="60"/>
                                    <circle
                                        class="progress-ring-circle"
                                        stroke="#667eea"
                                        stroke-width="10"
                                        fill="transparent"
                                        r="50"
                                        cx="60"
                                        cy="60"
                                        stroke-dasharray="{{ 2 * pi() * 50 * 0.75 }} {{ 2 * pi() * 50 }}"/>
                                </svg>
                            </div>
                            <h4 class="mt-3">€{{ number_format($analytics['events']['total_revenue'], 2) }}</h4>
                            <small class="text-muted">Ricavi Totali</small>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Performance Metrics -->
            <div class="chart-container">
                <h5 class="chart-title">
                    <i class="ph ph-gauge me-2"></i>Performance & Qualità
                </h5>
                <canvas id="performanceChart" height="100"></canvas>
            </div>

            <!-- Geographical Distribution -->
            <div class="chart-container">
                <h5 class="chart-title">
                    <i class="ph ph-map-pin me-2"></i>Distribuzione Geografica
                </h5>
                <canvas id="geoChart" height="120"></canvas>
            </div>
        </div>

        <!-- Right Column: Insights & Details -->
        <div class="col-lg-4">

            <!-- Top Events -->
            <div class="chart-container">
                <h5 class="chart-title">
                    <i class="ph ph-trophy me-2"></i>Top Eventi del Periodo
                </h5>
                @if(count($analytics['events']['top_events']) > 0)
                    <ul class="top-events-list">
                        @foreach($analytics['events']['top_events'] as $event)
                            <li>
                                <div class="event-info">
                                    <div class="event-title">{{ $event['title'] }}</div>
                                    <div class="event-meta">{{ $event['date'] }} • {{ $event['city'] }}</div>
                                </div>
                                <div class="participant-count">
                                    {{ $event['participants'] }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-4">
                        <i class="ph ph-calendar-x display-4 text-muted mb-3"></i>
                        <p class="text-muted">Nessun evento nel periodo selezionato</p>
                    </div>
                @endif
            </div>

            <!-- Key Insights -->
            <div class="chart-container">
                <h5 class="chart-title">
                    <i class="ph ph-lightbulb me-2"></i>Insights Chiave
                </h5>

                <!-- Best Day Insight -->
                @if(count($analytics['events']['best_days_of_week']) > 0)
                <div class="insight-card">
                    <div class="insight-icon">
                        <i class="ph ph-calendar"></i>
                    </div>
                    <h6>Giorno Migliore</h6>
                    <p class="mb-1">{{ array_keys($analytics['events']['best_days_of_week'])[0] }} è il tuo giorno più produttivo</p>
                    <small class="text-muted">{{ array_values($analytics['events']['best_days_of_week'])[0] }} eventi organizzati</small>
                </div>
                @endif

                <!-- Duration Insight -->
                @if($analytics['events']['optimal_duration'] > 0)
                <div class="insight-card">
                    <div class="insight-icon">
                        <i class="ph ph-clock"></i>
                    </div>
                    <h6>Durata Ottimale</h6>
                    <p class="mb-1">I tuoi eventi durano in media {{ $analytics['events']['optimal_duration'] }} ore</p>
                    <small class="text-muted">Durata ideale per il poetry slam</small>
                </div>
                @endif

                <!-- Success Rate Insight -->
                <div class="insight-card">
                    <div class="insight-icon">
                        <i class="ph ph-check-circle"></i>
                    </div>
                    <h6>Tasso di Successo</h6>
                    <p class="mb-1">{{ $analytics['events']['event_success_rate'] }}% dei tuoi eventi si completano con successo</p>
                    <small class="text-muted">Tasso di cancellazione: {{ $analytics['events']['cancellation_rate'] }}%</small>
                </div>

                <!-- Engagement Insight -->
                <div class="insight-card">
                    <div class="insight-icon">
                        <i class="ph ph-heart"></i>
                    </div>
                    <h6>Engagement Community</h6>
                    <p class="mb-1">{{ $analytics['engagement']['user_retention'] }}% di retention dei partecipanti</p>
                    <small class="text-muted">{{ $analytics['overview']['repeat_participants'] }} partecipanti ricorrenti</small>
                </div>
            </div>

            <!-- Real-time Stats -->
            <div class="chart-container">
                <h5 class="chart-title">
                    <i class="ph ph-activity me-2"></i>Statistiche Live
                </h5>
                <div id="realtimeStats">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="h4 text-primary" id="activeEvents">-</div>
                            <small class="text-muted">Eventi Attivi</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h4 text-warning" id="pendingResponses">-</div>
                            <small class="text-muted">Risposte Pendenti</small>
                        </div>
                        <div class="col-6">
                            <div class="h4 text-success" id="newRequestsToday">-</div>
                            <small class="text-muted">Richieste Oggi</small>
                        </div>
                        <div class="col-6">
                            <div class="h4 text-info" id="eventsNext7Days">-</div>
                            <small class="text-muted">Prossimi 7gg</small>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted">Ultimo aggiornamento: <span id="lastUpdate">-</span></small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Controls -->
<div class="export-controls">
    <div class="dropdown">
        <button class="export-btn dropdown-toggle" data-bs-toggle="dropdown">
            <i class="ph ph-download me-2"></i>Esporta Dati
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" onclick="exportData('json')">
                <i class="ph ph-file-code me-2"></i>JSON
            </a></li>
            <li><a class="dropdown-item" href="#" onclick="exportData('csv')">
                <i class="ph ph-file-csv me-2"></i>CSV
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#" onclick="generateReport()">
                <i class="ph ph-file-pdf me-2"></i>Report PDF
            </a></li>
        </ul>
    </div>
</div>
@endsection

@section('script')
<script>
let charts = {};
let realtimeInterval;

document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    startRealtimeUpdates();
    setupTimeframeSelector();
});

function initializeCharts() {
    // Event Creation Trend Chart
    const eventTrendData = @json($analytics['trends']['event_creation_trend']);
    charts.eventTrend = new Chart(document.getElementById('eventCreationChart'), {
        type: 'line',
        data: {
            labels: Object.keys(eventTrendData),
            datasets: [{
                label: 'Eventi Creati',
                data: Object.values(eventTrendData),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    @if($analytics['events']['total_revenue'] > 0)
    // Revenue Chart
    const revenueData = @json($analytics['events']['revenue_by_month']);
    charts.revenue = new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(revenueData),
            datasets: [{
                label: 'Ricavi (€)',
                data: Object.values(revenueData),
                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '€' + value;
                        }
                    }
                }
            }
        }
    });
    @endif

    // Performance Chart
    charts.performance = new Chart(document.getElementById('performanceChart'), {
        type: 'radar',
        data: {
            labels: ['Efficienza', 'Qualità Organizzazione', 'Soddisfazione', 'Completamento', 'Pianificazione'],
            datasets: [{
                label: 'Performance Score',
                data: [
                    {{ $analytics['performance']['efficiency_score'] }},
                    {{ $analytics['performance']['organization_quality'] }},
                    {{ $analytics['performance']['participant_satisfaction'] }},
                    {{ $analytics['performance']['event_completion_rate'] }},
                    {{ $analytics['performance']['planning_effectiveness'] }}
                ],
                backgroundColor: 'rgba(102, 126, 234, 0.2)',
                borderColor: '#667eea',
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#667eea'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 20
                    }
                }
            }
        }
    });

    // Geographical Chart
    const geoData = @json($analytics['geographical']['events_by_city']);
    charts.geo = new Chart(document.getElementById('geoChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(geoData),
            datasets: [{
                data: Object.values(geoData),
                backgroundColor: [
                    '#667eea',
                    '#764ba2',
                    '#f093fb',
                    '#f5576c',
                    '#4facfe',
                    '#00f2fe'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function startRealtimeUpdates() {
    // Initial load
    updateRealtimeStats();

    // Update every 30 seconds
    realtimeInterval = setInterval(updateRealtimeStats, 30000);
}

function updateRealtimeStats() {
    fetch('/analytics/realtime')
        .then(response => response.json())
        .then(data => {
            document.getElementById('activeEvents').textContent = data.active_events;
            document.getElementById('pendingResponses').textContent = data.pending_responses;
            document.getElementById('newRequestsToday').textContent = data.new_requests_today;
            document.getElementById('eventsNext7Days').textContent = data.events_next_7_days;

            const lastUpdate = new Date(data.last_update);
            document.getElementById('lastUpdate').textContent = lastUpdate.toLocaleTimeString('it-IT');
        })
        .catch(error => {
            console.error('Error updating realtime stats:', error);
        });
}

function setupTimeframeSelector() {
    document.querySelectorAll('.timeframe-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const timeframe = this.dataset.timeframe;
            window.location.href = `?timeframe=${timeframe}`;
        });
    });
}

function refreshAnalytics() {
    window.location.reload();
}

function exportAnalytics() {
    exportData('json');
}

function exportData(format) {
    const timeframe = '{{ $timeframe }}';
    window.open(`/analytics/export?format=${format}&timeframe=${timeframe}`, '_blank');
}

function generateReport() {
    // Would implement PDF generation
    alert('Generazione report PDF - Feature in sviluppo');
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (realtimeInterval) {
        clearInterval(realtimeInterval);
    }

    Object.values(charts).forEach(chart => {
        if (chart) chart.destroy();
    });
});
</script>
@endsection
