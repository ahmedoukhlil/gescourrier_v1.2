<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Collection;

class DailyDigest extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    public $user;
    
    /**
     * The notifications collection.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $notifications;
    
    /**
     * Stats about notifications.
     *
     * @var array
     */
    public $stats;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\User  $user
     * @param  \Illuminate\Database\Eloquent\Collection  $notifications
     * @return void
     */
    public function __construct(User $user, Collection $notifications)
    {
        $this->user = $user;
        $this->notifications = $notifications;
        $this->stats = $this->calculateStats($notifications);
    }
    
    /**
     * Calculate statistics for the notifications
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $notifications
     * @return array
     */
    protected function calculateStats(Collection $notifications)
    {
        $totalCount = $notifications->count();
        
        // Count notifications by type
        $byType = $notifications->groupBy('type')->map->count();
        
        // Count notifications by source
        $bySource = $notifications->groupBy('source_type')->map->count();
        
        // Count by importance level
        $byImportance = $notifications->groupBy('importance')->map->count();
        
        // Get high importance notifications
        $highImportance = $notifications->where('importance', '>=', 4)->count();
        
        return [
            'total' => $totalCount,
            'byType' => $byType,
            'bySource' => $bySource,
            'byImportance' => $byImportance,
            'highImportance' => $highImportance,
        ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Daily Notification Digest - " . now()->format('M j, Y'))
                    ->markdown('emails.daily-digest')
                    ->with([
                        'user' => $this->user,
                        'notifications' => $this->notifications,
                        'stats' => $this->stats,
                        'date' => now()->format('F j, Y'),
                    ]);
    }
}