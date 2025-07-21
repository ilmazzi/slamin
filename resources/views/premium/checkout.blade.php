@extends('layout.master')

@section('title', __('premium.checkout') . ' - ' . $package->name)

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">{{ __('premium.checkout') }}</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> {{ __('dashboard.dashboard') }}
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('premium.index') }}" class="f-s-14 f-w-500">{{ __('premium.packages') }}</a>
                    </li>
                    <li class="">
                        <a href="{{ route('premium.show', $package) }}" class="f-s-14 f-w-500">{{ $package->name }}</a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">{{ __('premium.checkout') }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row">
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <div class="card card-light-primary">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-credit-card f-s-16 me-2"></i>
                            {{ __('premium.payment_method') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('premium.purchase', $package) }}" method="POST" id="checkout-form">
                            @csrf

                            <!-- Payment Method Selection -->
                            <div class="mb-4">
                                <label class="form-label fw-medium">{{ __('premium.payment_method') }}</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check border rounded p-3">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                   id="card" value="card" checked>
                                            <label class="form-check-label" for="card">
                                                <i class="ph-duotone ph-credit-card f-s-20 me-2 text-primary"></i>
                                                Carta di Credito/Debito
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check border rounded p-3">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                   id="paypal" value="paypal">
                                            <label class="form-check-label" for="paypal">
                                                <i class="ph-duotone ph-paypal-logo f-s-20 me-2 text-info"></i>
                                                PayPal
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Details (for card payment) -->
                            <div id="card-details">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-medium">Numero Carta</label>
                                        <input type="text" class="form-control" placeholder="1234 5678 9012 3456"
                                               name="card_number" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">Data Scadenza</label>
                                        <input type="text" class="form-control" placeholder="MM/YY"
                                               name="card_expiry" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">CVV</label>
                                        <input type="text" class="form-control" placeholder="123"
                                               name="card_cvv" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-medium">Nome Intestatario</label>
                                        <input type="text" class="form-control" placeholder="Nome Cognome"
                                               name="card_holder" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms_accepted"
                                           name="terms_accepted" required>
                                    <label class="form-check-label" for="terms_accepted">
                                        {{ __('premium.terms_accepted') }}
                                        <a href="#" class="text-primary">Termini e Condizioni</a>
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">
                                    <i class="ph-duotone ph-lock f-s-16 me-2"></i>
                                    {{ __('premium.purchase') }} - €{{ $package->formatted_price }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="card card-light-info mt-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="ph-duotone ph-shield-check f-s-24 text-success me-3"></i>
                            <div>
                                <h6 class="mb-1">Pagamento Sicuro</h6>
                                <p class="mb-0 text-muted small">
                                    I tuoi dati di pagamento sono protetti con crittografia SSL.
                                    Non memorizziamo mai i dati della tua carta.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card card-light-success">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-receipt f-s-16 me-2"></i>
                            Riepilogo Ordine
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Package Info -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                @if($package->slug === 'poet_basic')
                                    <i class="ph-duotone ph-pen-nib f-s-32 text-primary"></i>
                                @elseif($package->slug === 'poet_pro')
                                    <i class="ph-duotone ph-pen-nib-straight f-s-32 text-warning"></i>
                                @elseif($package->slug === 'poet_elite')
                                    <i class="ph-duotone ph-crown f-s-32 text-success"></i>
                                @elseif($package->slug === 'organizer')
                                    <i class="ph-duotone ph-users f-s-32 text-info"></i>
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $package->name }}</h6>
                                <small class="text-muted">{{ $package->video_limit }} {{ __('videos.videos_allowed') }}</small>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $package->name }}</span>
                                <span>€{{ $package->formatted_price }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>IVA (22%)</span>
                                <span>€{{ number_format($package->price * 0.22, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Totale</span>
                                <span>€{{ number_format($package->price * 1.22, 2) }}</span>
                            </div>
                        </div>

                        <!-- Features Preview -->
                        <div class="mt-4">
                            <h6 class="mb-3">{{ __('premium.features') }}</h6>
                            @if($package->features)
                                @foreach(array_slice($package->features, 0, 3) as $feature => $enabled)
                                    @if($enabled)
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="ph-duotone ph-check-circle f-s-16 text-success me-2"></i>
                                            <small>{{ __('premium.' . $feature) }}</small>
                                        </div>
                                    @endif
                                @endforeach
                                @if(count($package->features) > 3)
                                    <small class="text-muted">+{{ count($package->features) - 3 }} altre caratteristiche</small>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Back Link -->
                <div class="text-center mt-3">
                    <a href="{{ route('premium.show', $package) }}" class="btn btn-outline-secondary">
                        <i class="ph-duotone ph-arrow-left me-1"></i>
                        {{ __('common.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkout-form');
    const submitBtn = document.getElementById('submit-btn');
    const cardDetails = document.getElementById('card-details');

    // Toggle card details based on payment method
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        });
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ph-duotone ph-circle-notch f-s-16 me-2 fa-spin"></i>Elaborazione...';

        // Submit form
        this.submit();
    });
});
</script>
@endpush
@endsection
