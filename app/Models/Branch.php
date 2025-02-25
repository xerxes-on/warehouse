<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $fillable = ['name', 'location'];

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'branch_id', 'id');
    }
    public function sendMessage(int $conversationId, string $messageText, ?int $replyToMessageId = null): Message|JsonResponse
    {
        $user = Auth::user();

        $conversation = Conversation::findOrFail($conversationId);

        if (!$conversation->members()->where('user_id', $user->id)->exists()) {
            return $this->commonHelper->returnResponse("Siz ushbu suhbatning aʼzosi emassiz", [], ResponseAlias::HTTP_FORBIDDEN);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => $messageText,
            'message_type' => ChatMessageType::TEXT,
            'reply_to_message_id' => $replyToMessageId,
        ]);
        Log::info("Event Dispatching: MessageSent", ['message' => $message]);

        broadcast(new MessageSent($message));
        MessageSent::dispatch($message);
        return $message;
    }
}
