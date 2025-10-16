<?php

namespace App\Http\Middleware\Chat;

use Closure;
use Illuminate\Http\Request;
use App\Models\Chat\Conversation;
use Symfony\Component\HttpFoundation\Response;

class BelongsToConversation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = $request->user();
        
        // Get the conversation from route model binding or find it manually
        $conversation = $request->route('conversation');
        
        // If it's not a Conversation model instance, it's probably an ID
        if (!($conversation instanceof Conversation)) {
            $conversationId = $conversation;
            $conversation = Conversation::findOrFail($conversationId);
        }

        if (! $user || ! $user->belongsToConversation($conversation)
        ) {
            abort(403, 'Forbidden');
        }

        // Make sure the conversation is available to the route
        $request->route()->setParameter('conversation', $conversation);

        return $next($request);

    }
}
