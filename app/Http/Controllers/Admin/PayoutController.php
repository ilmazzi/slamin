<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TranslationPayment;
use App\Services\PayoutService;
use Illuminate\Support\Facades\Log;

class PayoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Lista tutti i payout
     */
    public function index()
    {
        $payments = TranslationPayment::with(['poem', 'client', 'translator', 'gigApplication.gig'])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_payments' => TranslationPayment::where('status', 'completed')->count(),
            'total_amount' => TranslationPayment::where('status', 'completed')->sum('amount'),
            'total_commission' => TranslationPayment::where('status', 'completed')->sum('commission_total'),
            'pending_payouts' => TranslationPayment::where('payout_status', 'pending')->count(),
            'transferred_payouts' => TranslationPayment::where('payout_status', 'transferred')->count(),
            'manual_payouts' => TranslationPayment::where('payout_status', 'manual_required')->count(),
        ];

        return view('admin.payouts.index', compact('payments', 'stats'));
    }

    /**
     * Mostra dettagli di un payout
     */
    public function show(TranslationPayment $payment)
    {
        $payment->load(['poem', 'client', 'translator', 'gigApplication.gig']);

        return view('admin.payouts.show', compact('payment'));
    }

    /**
     * Processa un payout specifico
     */
    public function process(Request $request, TranslationPayment $payment)
    {
        $request->validate([
            'action' => 'required|in:transfer,manual,skip',
            'notes' => 'nullable|string|max:500',
        ]);

        $payoutService = new PayoutService();

        switch ($request->action) {
            case 'transfer':
                $result = $payoutService->transferToTranslator($payment);
                break;

            case 'manual':
                $result = $payoutService->createManualPayout($payment, $request->notes);
                break;

            case 'skip':
                $payment->update([
                    'payout_status' => 'skipped',
                    'payout_notes' => $request->notes ?? 'Payout saltato dall\'admin',
                ]);
                $result = ['success' => true, 'message' => 'Payout saltato'];
                break;
        }

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    /**
     * Processa tutti i payout pending
     */
    public function processAll()
    {
        $payoutService = new PayoutService();
        $results = $payoutService->processPendingPayouts();

        $successCount = collect($results)->where('success', true)->count();
        $failCount = collect($results)->where('success', false)->count();

        return redirect()->back()->with('success',
            "Processati {$successCount} payout con successo, {$failCount} falliti"
        );
    }

    /**
     * Dashboard payout
     */
    public function dashboard()
    {
        $recentPayments = TranslationPayment::with(['poem', 'translator'])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $pendingPayouts = TranslationPayment::with(['poem', 'translator'])
            ->where('payout_status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        $manualPayouts = TranslationPayment::with(['poem', 'translator'])
            ->where('payout_status', 'manual_required')
            ->orderBy('created_at', 'asc')
            ->get();

        $stats = [
            'total_revenue' => TranslationPayment::where('status', 'completed')->sum('commission_total'),
            'total_paid_out' => TranslationPayment::where('payout_status', 'transferred')->sum('translator_amount'),
            'pending_amount' => TranslationPayment::where('payout_status', 'pending')->sum('translator_amount'),
            'manual_amount' => TranslationPayment::where('payout_status', 'manual_required')->sum('translator_amount'),
        ];

        return view('admin.payouts.dashboard', compact(
            'recentPayments',
            'pendingPayouts',
            'manualPayouts',
            'stats'
        ));
    }
}
