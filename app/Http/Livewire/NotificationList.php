<?php

namespace App\Http\Livewire;

use App\Models\Notification;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class NotificationList extends Component
{
    use WithPagination;
    
    public $filter = 'all'; // 'all', 'unread', 'read'
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    
    protected $queryString = [
        'filter' => ['except' => 'all'],
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
    ];
    
    protected $listeners = [
        'refreshNotifications' => '$refresh',
        'echo-private:notifications.{userId},NewNotification' => '$refresh',
    ];
    
    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $userId = Auth::id();
        
        Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        $this->dispatchBrowserEvent('notifications-marked-read');
    }
    
    /**
     * Mark a specific notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        if ($notification->user_id === Auth::id()) {
            $notification->markAsRead();
        }
    }
    
    /**
     * Toggle the read status of a notification
     */
    public function toggleReadStatus($id)
    {
        $notification = Notification::findOrFail($id);
        
        if ($notification->user_id === Auth::id()) {
            $notification->is_read = !$notification->is_read;
            $notification->save();
        }
    }
    
    /**
     * Delete a notification
     */
    public function delete($id)
    {
        $notification = Notification::findOrFail($id);
        
        if ($notification->user_id === Auth::id()) {
            $notification->delete();
        }
    }
    
    /**
     * Delete all read notifications
     */
    public function deleteAllRead()
    {
        $userId = Auth::id();
        
        Notification::where('user_id', $userId)
            ->where('is_read', true)
            ->delete();
            
        $this->dispatchBrowserEvent('read-notifications-deleted');
    }
    
    /**
     * Set sorting
     */
    public function sort($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    /**
     * Get the unread count for displaying in the UI
     */
    public function getUnreadCountProperty()
    {
        return Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();
    }

    public function render()
    {
        $userId = Auth::id();
        
        $query = Notification::where('user_id', $userId);
        
        // Apply filter
        if ($this->filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($this->filter === 'read') {
            $query->where('is_read', true);
        }
        
        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }
        
        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);
        
        // Get paginated results
        $notifications = $query->paginate($this->perPage);
        
        return view('livewire.notification-list', [
            'notifications' => $notifications,
            'unreadCount' => $this->unreadCount,
        ]);
    }
}