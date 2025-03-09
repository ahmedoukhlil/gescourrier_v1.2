<?php

namespace App\Http\Livewire;

use App\Models\Notification;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationIndicator extends Component
{
    public $unreadCount = 0;
    public $notifications = [];
    public $showDropdown = false;
    public $user;

    // Listen for new notification events broadcast through Livewire
    protected $listeners = [
        'refreshNotifications' => 'loadNotifications',
        'echo-private:notifications.{userId},NewNotification' => 'handleNewNotification',
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadNotifications();
    }

    /**
     * Load the most recent notifications for the user
     */
    public function loadNotifications()
    {
        $this->unreadCount = Notification::where('user_id', $this->user->id)
            ->where('is_read', false)
            ->count();

        // Get recent notifications (read and unread) for the dropdown
        $this->notifications = Notification::where('user_id', $this->user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->toArray();
    }

    /**
     * Handle real-time notification via Pusher
     */
    public function handleNewNotification($notification)
    {
        // Simply reload notifications - could be optimized to just add the new one
        $this->loadNotifications();
        
        // Show browser notification if enabled
        $this->dispatchBrowserEvent('show-browser-notification', [
            'title' => $notification['title'],
            'body' => $notification['content'],
            'icon' => '/path/to/icon.png',
            'link' => $notification['link']
        ]);
    }

    /**
     * Toggle the notification dropdown
     */
    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    /**
     * Close the notification dropdown
     */
    public function closeDropdown()
    {
        $this->showDropdown = false;
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($notificationId)
    {
        $notification = Notification::findOrFail($notificationId);
        
        // Ensure this notification belongs to the authenticated user
        if ($notification->user_id === $this->user->id) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', $this->user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        $this->loadNotifications();
    }

    /**
     * Navigate to notification settings
     */
    public function goToSettings()
    {
        return redirect()->route('notifications.settings');
    }

    /**
     * View all notifications
     */
    public function viewAll()
    {
        return redirect()->route('notifications.index');
    }

    public function render()
    {
        return view('livewire.notification-indicator');
    }
}