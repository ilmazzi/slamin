@extends('layout.master')

@section('title', 'Pagamento Traduzione - ' . $application->gig->poem->title)

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb start -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">Pagamento Traduzione</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> Dashboard
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('translations.index') }}" class="f-s-14 f-w-500">Traduzioni</a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">Pagamento</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Breadcrumb end -->

    <div class="row">
        <div class="col-lg-8">
            @if($existingPayment && $existingPayment->isCompleted())
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="ph-duotone ph-check-circle f-s-64 text-success"></i>
                        </div>
                        <h5 class="text-success">Pagamento Completato!</h5>
                        <p class="text-muted">Questo pagamento è stato processato con successo il {{ $existingPayment->paid_at->format('d/m/Y H:i') }}.</p>
                        <a href="{{ route('translations.payment.success', $existingPayment) }}" class="btn btn-primary">
                            <i class="ph-duotone ph-eye f-s-16 me-2"></i>Visualizza Dettagli
                        </a>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="tab-heading mb-3">Metodo di Pagamento</h5>
                            </div>

                            <!-- Payment Method Selection -->
                            <div class="col-12">
                                <div class="card shadow-none">
                                    <div class="card-body select-content">
                                        <div class="mb-3">
                                            <label class="check-box">
                                                <input type="radio" name="payment_method" value="stripe" id="stripe_method" checked>
                                                <span class="radiomark outline-secondary"></span>
                                                <span class="d-flex align-items-center">
                                                    <img src="{{asset('assets/images/checkbox-radio/logo1.png')}}" class="w-35 h-35 me-2" alt="{{ __('common.credit_card') }}">
                                                    <span class="fs-6 tab-heading">Carta di Credito/Debito</span>
                                                </span>
                                            </label>
                                        </div>
                                        <div id="stripe-payment-form" class="payment-form">
                                            <form class="app-form">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nome Intestatario</label>
                                                            <input type="text" class="form-control" placeholder="{{ __('common.full_name') }}" id="card-holder">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Numero Carta</label>
                                                            <div id="card-element" class="form-control" style="height: 50px; padding: 10px;">
                                                                <!-- Stripe Elements will create form elements here -->
                                                            </div>
                                                            <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Data Scadenza</label>
                                                            <input type="text" class="form-control" placeholder="MM/YY" id="card-expiry">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">CVV</label>
                                                            <input type="text" class="form-control" placeholder="123" id="card-cvv">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card shadow-none">
                                    <div class="card-body select-content">
                                        <div class="position-relative">
                                            <label class="check-box">
                                                <input type="radio" name="payment_method" value="paypal" id="paypal_method">
                                                <span class="radiomark outline-secondary position-absolute"></span>
                                                <span class="d-flex align-items-center mg-s-25">
                                                    <img src="{{asset('assets/images/checkbox-radio/logo3.png')}}" class="w-35 h-35" alt="{{ __('common.paypal') }}">
                                                    <span class="ms-2">
                                                        <span class="fs-6 tab-heading">PayPal</span>
                                                        <span class="d-block text-secondary">Paga con PayPal</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PayPal Payment Form -->
                            <div class="col-12">
                                <div id="paypal-payment-form" class="payment-form d-none">
                                    <div class="card shadow-none">
                                        <div class="card-body text-center">
                                            <p class="text-muted mb-3">Clicca il bottone qui sotto per completare il pagamento con PayPal</p>
                                            <div id="paypal-button-container"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="col-12">
                                <div class="mb-3 form-check d-flex p-0">
                                    <input type="checkbox" class="m-1 form-check-input" id="terms_accepted" required>
                                    <label class="form-check-label" for="terms_accepted">
                                        Accetto i <a href="#" class="text-primary">termini e condizioni</a> del servizio
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12">
                                <div class="text-end">
                                    <button type="button" id="submit-payment" class="btn btn-primary">
                                        <i class="ph-duotone ph-lock f-s-16 me-2"></i>
                                        <span id="button-text">Paga {{ number_format($application->gig->compensation, 2) }} €</span>
                                        <span id="spinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="tab-heading mb-3">Riepilogo Ordine</h5>

                    @php
                        $commissionData = \App\Services\PaymentService::calculateCommission($application->gig->compensation);
                    @endphp

                    <!-- Service Details -->
                    <div class="checkout-cart-box">
                        <div class="cart-images d-flex-center flex-shrink-0">
                            <i class="ph-duotone ph-book f-s-32 text-primary"></i>
                        </div>
                        <div class="ms-2 flex-grow-1">
                            <h6>{{ $application->gig->poem->title }}</h6>
                            <p class="text-muted small">{{ Str::limit($application->gig->poem->content, 60) }}</p>
                            <p><span>Lingue:</span>
                                @foreach($application->gig->target_languages as $lang)
                                    <span class="badge bg-primary me-1">{{ strtoupper($lang) }}</span>
                                @endforeach
                            </p>
                        </div>
                        <div class="cart-price-box">
                            <h5>{{ number_format($application->gig->compensation, 2) }} €</h5>
                        </div>
                    </div>

                    <!-- Translator Info -->
                    <div class="checkout-cart-box">
                        <div class="cart-images d-flex-center flex-shrink-0">
                            <i class="ph-duotone ph-user f-s-32 text-success"></i>
                        </div>
                        <div class="ms-2 flex-grow-1">
                            <h6>Traduttore</h6>
                            <p class="text-muted small">{{ $application->user->name }}</p>
                            <p class="text-muted small">{{ $application->user->email }}</p>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="pricing-details">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Traduzione</th>
                                    <th scope="col" class="text-end">{{ number_format($application->gig->compensation, 2) }} €</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($commissionData['commission_total'] > 0)
                                <tr>
                                    <td>Commissione piattaforma</td>
                                    <td class="text-end">-{{ number_format($commissionData['commission_total'], 2) }} €</td>
                                </tr>
                                <tr>
                                    <td><strong>Al traduttore</strong></td>
                                    <td class="text-end"><strong>{{ number_format($commissionData['translator_amount'], 2) }} €</strong></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Totale da pagare</th>
                                    <th class="text-end">{{ number_format($application->gig->compensation, 2) }} €</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <!-- Security Notice -->
                    <div class="alert alert-light-border-success d-flex align-items-center mt-3" role="alert">
                        <p class="mb-0">
                            <i class="ph-duotone ph-shield-check f-s-18 me-2"></i>
                            Pagamento sicuro protetto da SSL
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id={{ \App\Models\SystemSetting::get('paypal_client_id', 'test') }}&currency=EUR&intent=capture"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inizializza Stripe
    const stripe = Stripe('{{ \App\Models\SystemSetting::get("stripe_public_key") }}');
    const elements = stripe.elements();

    // Crea elemento carta
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#424770',
                '::placeholder': {
                    color: '#aab7c4',
                },
            },
        },
    });

    cardElement.mount('#card-element');

    // Gestione errori carta
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Gestione selezione metodo di pagamento
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const selectedMethod = this.value;

            // Nascondi tutti i form
            document.querySelectorAll('.payment-form').forEach(form => {
                form.classList.add('d-none');
            });

            // Mostra il form selezionato
            if (selectedMethod === 'stripe') {
                document.getElementById('stripe-payment-form').classList.remove('d-none');
            } else if (selectedMethod === 'paypal') {
                document.getElementById('paypal-payment-form').classList.remove('d-none');
                // Inizializza PayPal dopo un piccolo delay per assicurarsi che il container sia visibile
                setTimeout(() => {
                    initializePayPal();
                }, 100);
            }
        });
    });

    // Inizializza PayPal
    function initializePayPal() {
        const container = document.getElementById('paypal-button-container');

        // Pulisci il container se ha già contenuto
        if (container.hasChildNodes()) {
            container.innerHTML = '';
        }

        // Mostra loading
        container.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Caricamento...</span></div></div>';

        // Attendi che PayPal SDK sia caricato
        function waitForPayPal() {
            console.log('PayPal Client ID:', '{{ \App\Models\SystemSetting::get("paypal_client_id") }}');
            if (typeof paypal !== 'undefined' && paypal.Buttons) {
                try {
                    paypal.Buttons({
                        style: {
                            layout: 'vertical',
                            color: 'blue',
                            shape: 'rect',
                            label: 'paypal',
                            height: 45
                        },
                        createOrder: function(data, actions) {
                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: '{{ $application->gig->compensation }}',
                                        currency_code: 'EUR'
                                    },
                                    description: 'Traduzione: {{ $application->gig->poem->title }}'
                                }]
                            });
                        },
                        onApprove: function(data, actions) {
                            return actions.order.capture().then(function(details) {
                                // Conferma pagamento PayPal
                                fetch('{{ route("translations.payment.confirm", $application) }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({
                                        payment_method: 'paypal',
                                        paypal_order_id: data.orderID,
                                        paypal_payer_id: details.payer.payer_id
                                    })
                                }).then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        Swal.fire({
                                            title: 'Pagamento Completato!',
                                            text: 'La tua traduzione è stata pagata con successo.',
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        }).then(() => {
                                            window.location.href = result.redirect_url;
                                        });
                                    }
                                });
                            });
                        },
                        onError: function(err) {
                            console.error('PayPal Error:', err);
                            Swal.fire({
                                title: 'Errore PayPal',
                                text: 'Si è verificato un errore durante il pagamento. Riprova.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }).render('#paypal-button-container');
                } catch (error) {
                    console.error('Errore nel rendering PayPal:', error);
                    container.innerHTML = '<p class="text-danger">Errore nel caricamento di PayPal. Riprova.</p>';
                }
            } else {
                // Riprova dopo 500ms
                setTimeout(waitForPayPal, 500);
            }
        }

        // Inizia l'attesa
        waitForPayPal();
    }

    // Gestisci invio pagamento
    document.getElementById('submit-payment').addEventListener('click', async function(event) {
        event.preventDefault();

        const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
        const termsAccepted = document.getElementById('terms_accepted').checked;

        if (!termsAccepted) {
            Swal.fire({
                title: 'Termini e Condizioni',
                text: 'Devi accettare i termini e condizioni per procedere.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (selectedMethod === 'stripe') {
            await processStripePayment();
        } else if (selectedMethod === 'paypal') {
            // Per PayPal, il bottone è gestito direttamente dal SDK PayPal
            // Non serve fare nulla qui
        }
    });

    // Processa pagamento Stripe
    async function processStripePayment() {
        const submitButton = document.getElementById('submit-payment');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');

        submitButton.disabled = true;
        buttonText.textContent = 'Elaborazione...';
        spinner.classList.remove('d-none');

        try {
            // Crea PaymentIntent
            const response = await fetch('{{ route("translations.payment.create-intent", $application) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const { client_secret } = await response.json();

            // Conferma pagamento con Stripe
            const { error, paymentIntent } = await stripe.confirmCardPayment(client_secret, {
                payment_method: {
                    card: cardElement,
                }
            });

            if (error) {
                Swal.fire({
                    title: 'Errore Pagamento',
                    text: error.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            } else {
                // Conferma pagamento sul server
                const confirmResponse = await fetch('{{ route("translations.payment.confirm", $application) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        payment_method: 'stripe',
                        payment_intent_id: paymentIntent.id
                    })
                });

                const result = await confirmResponse.json();

                if (result.success) {
                    Swal.fire({
                        title: 'Pagamento Completato!',
                        text: 'La tua traduzione è stata pagata con successo.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = result.redirect_url;
                    });
                }
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                title: 'Errore',
                text: 'Si è verificato un errore durante il pagamento.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        } finally {
            // Ripristina button
            submitButton.disabled = false;
            buttonText.textContent = 'Paga {{ number_format($application->gig->compensation, 2) }} €';
            spinner.classList.add('d-none');
        }
    }
});
</script>
@endpush
@endsection
