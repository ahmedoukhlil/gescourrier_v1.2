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

    /**
     * Get an icon based on notification type and source
     * Cette méthode est rendue publique pour être utilisée par d'autres composants
     */
    public function getNotificationIcon($notification)
    {
        // Source-based icons
        if ($notification['source_type'] === 'courrier') {
            return '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>';
        } elseif ($notification['source_type'] === 'annotation') {
            return '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
            </svg>';
        } elseif ($notification['source_type'] === 'draft') {
            return '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>';
        } elseif ($notification['source_type'] === 'courrier-sortant') {
            return '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
            </svg>';
        }

        // Type-based icons (fallback)
        switch ($notification['type']) {
            case 'success':
                return '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>';
            case 'warning':
                return '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>';
            case 'error':
                return '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>';
            default: // info
                return '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>';
        }
    }

    public function render()
    {
        return view('livewire.notification-indicator');
    }
}