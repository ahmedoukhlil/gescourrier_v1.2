<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The user ID.
     *
     * @var int
     */
    public $userId;
    
    /**
     * The notification data.
     *
     * @var array
     */
    public $notification;

    /**
     * Create a new event instance.
     *
     * @param int $userId
     * @param \App\Models\Notification $notification
     * @return void
     */
    public function __construct(int $userId, Notification $notification)
    {
        $this->userId = $userId;
        
        // Convert the notification to an array with only the necessary attributes
        $this->notification = [
            'id' => $notification->id,
            'title' => $notification->title,
            'content' => $notification->content,
            'link' => $notification->link,
            'type' => $notification->type,
            'importance' => $notification->importance,
            'source_type' => $notification->source_type,
            'source_id' => $notification->source_id,
            'created_at' => $notification->created_at->toIso8601String(),
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('notifications.' . $this->userId);
    }
    
    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'NewNotification';
    }
}