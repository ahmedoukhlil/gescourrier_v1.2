<?php

namespace App\Mail;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The notification instance.
     *
     * @var \App\Models\Notification
     */
    public $notification;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Notification  $notification
     * @return void
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Set appropriate subject based on notification type
        $subject = $this->getSubject();
        
        return $this->subject($subject)
                    ->markdown('emails.notification')
                    ->with([
                        'title' => $this->notification->title,
                        'content' => $this->notification->content,
                        'link' => $this->notification->link,
                        'type' => $this->notification->type,
                        'importance' => $this->notification->importance,
                        'timestamp' => $this->notification->created_at,
                        'sourceType' => $this->notification->source_type,
                    ]);
    }
    
    /**
     * Get email subject based on notification type
     *
     * @return string
     */
    protected function getSubject()
    {
        // Prefix for high importance notifications
        $prefix = '';
        if ($this->notification->importance >= 4) {
            $prefix = '[IMPORTANT] ';
        } elseif ($this->notification->importance == 3) {
            $prefix = '[NOTICE] ';
        }
        
        // Add source type information if available
        $sourceInfo = '';
        if ($this->notification->source_type) {
            $sourceType = ucfirst(str_replace('_', ' ', $this->notification->source_type));
            $sourceInfo = " - {$sourceType}";
        }
        
        return $prefix . $this->notification->title . $sourceInfo;
    }
}