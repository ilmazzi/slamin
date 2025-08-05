<?php

namespace App\Http\Controllers;

use App\Events\CallRequest;
use App\Events\CallResponse;
use App\Events\WebRTCSignal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CallController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Inizia una chiamata
     */
    public function startCall(Request $request)
    {
        $request->validate([
            'target_user_id' => 'required|exists:users,id',
            'call_type' => 'required|in:audio,video',
            'offer' => 'sometimes|array'
        ]);

        $user = Auth::user();
        $targetUser = User::findOrFail($request->target_user_id);

        // Non può chiamare se stesso
        if ($user->id === $targetUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Non puoi chiamare te stesso.'
            ], 400);
        }

        try {
            // Broadcast della richiesta di chiamata via Reverb
            broadcast(new CallRequest($user, $targetUser->id, $request->call_type, $request->offer))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Chiamata avviata.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nell\'avvio della chiamata.'
            ], 500);
        }
    }

    /**
     * Risponde a una chiamata
     */
    public function answerCall(Request $request)
    {
        $request->validate([
            'from_user_id' => 'required|exists:users,id',
            'accepted' => 'required|boolean',
            'answer' => 'sometimes|array'
        ]);

        $user = Auth::user();
        $fromUser = User::findOrFail($request->from_user_id);

        try {
            // Broadcast della risposta alla chiamata via Reverb
            broadcast(new CallResponse($user, $fromUser->id, $request->accepted, $request->answer))->toOthers();

            return response()->json([
                'success' => true,
                'message' => $request->accepted ? 'Chiamata accettata.' : 'Chiamata rifiutata.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nella risposta alla chiamata.'
            ], 500);
        }
    }

    /**
     * Invia segnale WebRTC
     */
    public function sendSignal(Request $request)
    {
        $request->validate([
            'target_user_id' => 'required|exists:users,id',
            'signal' => 'required|array',
            'signal_type' => 'required|in:offer,answer,ice_candidate'
        ]);

        $user = Auth::user();

        try {
            // Broadcast del segnale WebRTC via Reverb
            broadcast(new WebRTCSignal($user->id, $request->target_user_id, $request->signal, $request->signal_type))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Segnale inviato.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nell\'invio del segnale.'
            ], 500);
        }
    }

    /**
     * Termina una chiamata
     */
    public function endCall(Request $request)
    {
        $request->validate([
            'target_user_id' => 'required|exists:users,id'
        ]);

        $user = Auth::user();

        try {
            // Broadcast della fine della chiamata via Reverb
            broadcast(new CallResponse($user, $request->target_user_id, false))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Chiamata terminata.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nella terminazione della chiamata.'
            ], 500);
        }
    }
} 