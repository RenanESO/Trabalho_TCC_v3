<?php

namespace App\Events;

use App\Models\GoogleToken;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class DeleteGoogleTokens
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __invoke()
    {
        // Obtém o ID do usuário atual
        $userId = Auth::id();

        // Se houver um usuário autenticado, deleta o token Google associado
        if ($userId) {
            GoogleToken::where('user_id', $userId)->delete();
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
