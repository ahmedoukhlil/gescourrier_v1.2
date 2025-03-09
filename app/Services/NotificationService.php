<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\NotificationPreference;
use App\Models\User;
use App\Mail\NotificationEmail;
use App\Mail\DailyDigest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;

class NotificationService
{
    /**
     * Send a notification to a user
     *
     * @param User $user The recipient user
     * @param string $title Notification title
     * @param string $content Notification content
     * @param string $link URL to view the related item
     * @param array $options Additional options (type, importance, source_type, source_id, etc.)
     * @return Notification|null
     */
    public function sendToUser(User $user, string $title, string $content, string $link = null, array $options = [])
    {
        // Get the user's notification preferences
        $preferences = $user->notificationPreferences ?? 
            NotificationPreference::where('user_id', $user->id)->first() ?? 
            null;
            
        // Create default preferences if none exist
        if (!$preferences) {
            $defaults = NotificationPreference::getDefaults();
            $preferences = $user->notificationPreferences()->create($defaults);
        }
        
        // Check if notifications are paused
        if ($preferences->isPaused()) {
            return null;
        }
        
        // Check notification type is enabled
        $notificationType = $options['notification_type'] ?? 'general';
        if (!$preferences->isTypeEnabled($notificationType)) {
            return null;
        }
        
        // Set default importance if not provided
        $importance = $options['importance'] ?? 1;
        
        // Check importance threshold
        if (!$preferences->shouldReceiveByImportance($importance)) {
            return null;
        }
        
        // Set default notification type
        $type = $options['type'] ?? 'info';
        
        // Create the notification record
        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'content' => $content,
            'link' => $link,
            'type' => $type,
            'importance' => $importance,
            'source_type' => $options['source_type'] ?? null,
            'source_id' => $options['source_id'] ?? null,
            'created_by' => $options['created_by'] ?? auth()->id(),
            'metadata' => $options['metadata'] ?? null,
        ]);
        
        // Send email notification if enabled and not set for daily digest
        if ($preferences->email_notifications && !$preferences->daily_digest) {
            $this->sendEmailNotification($user, $notification);
        }
        
        // Broadcast the notification for real-time updates
        $this->broadcastNotification($user, $notification);
        
        return $notification;
    }
    
    /**
     * Send notification to multiple users
     *
     * @param array $userIds Array of user IDs
     * @param string $title Notification title
     * @param string $content Notification content
     * @param string $link URL to view the related item
     * @param array $options Additional options
     * @return array Array of created notifications
     */
    public function sendToMany(array $userIds, string $title, string $content, string $link = null, array $options = [])
    {
        $notifications = [];
        
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $notification = $this->sendToUser($user, $title, $content, $link, $options);
                if ($notification) {
                    $notifications[] = $notification;
                }
            }
        }
        
        return $notifications;
    }
    
    /**
     * Send notification to all users with a specific role
     *
     * @param string|array $roles Role slug(s)
     * @param string $title Notification title
     * @param string $content Notification content
     * @param string $link URL to view the related item
     * @param array $options Additional options
     * @return array Array of created notifications
     */
    public function sendToRole($roles, string $title, string $content, string $link = null, array $options = [])
    {
        $users = User::whereHas('roles', function($query) use ($roles) {
            if (is_array($roles)) {
                $query->whereIn('slug', $roles);
            } else {
                $query->where('slug', $roles);
            }
        })->get();
        
        $notifications = [];
        
        foreach ($users as $user) {
            $notification = $this->sendToUser($user, $title, $content, $link, $options);
            if ($notification) {
                $notifications[] = $notification;
            }
        }
        
        return $notifications;
    }
    
    /**
     * Send email notification to a user
     *
     * @param User $user
     * @param Notification $notification
     * @return void
     */
    protected function sendEmailNotification(User $user, Notification $notification)
    {
        try {
            Mail::to($user->email)->send(new NotificationEmail($notification));
        } catch (\Exception $e) {
            Log::error('Failed to send notification email: ' . $e->getMessage());
        }
    }
    
    /**
     * Broadcast notification for real-time updates
     *
     * @param User $user
     * @param Notification $notification
     * @return void
     */
    protected function broadcastNotification(User $user, Notification $notification)
    {
        try {
            event(new \App\Events\NewNotification($user->id, $notification));
        } catch (\Exception $e) {
            Log::error('Failed to broadcast notification: ' . $e->getMessage());
        }
    }
    
    /**
     * Send daily digest emails to users who have opted for it
     *
     * @return void
     */
    public function sendDailyDigests()
    {
        // Get current time rounded to the nearest hour (HH:00)
        $currentHour = now()->format('H:00');
        
        // Find users who have opted for daily digest at this hour
        $users = User::whereHas('notificationPreferences', function($query) use ($currentHour) {
            $query->where('daily_digest', true)
                  ->where('daily_digest_time', $currentHour);
        })->get();
        
        foreach ($users as $user) {
            // Get unread notifications from the last 24 hours
            $notifications = Notification::where('user_id', $user->id)
                ->where('created_at', '>=', now()->subDay())
                ->orderBy('created_at', 'desc')
                ->get();
                
            if ($notifications->isNotEmpty()) {
                try {
                    Mail::to($user->email)->send(new DailyDigest($user, $notifications));
                    
                    // Mark all as read after sending digest
                    $notifications->each->markAsRead();
                } catch (\Exception $e) {
                    Log::error('Failed to send daily digest: ' . $e->getMessage());
                }
            }
        }
    }
}