<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('notifications.index');
    }

    /**
     * Display the notification settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        return view('notifications.settings');
    }

    /**
     * Mark all notifications as read.
     *
     * @return \Illuminate\Http\Response
     */
    public function markAllAsRead()
    {
        $userId = Auth::id();
        
        Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return back()->with('success', 'All notifications marked as read');
    }

    /**
     * Mark a specific notification as read.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        if ($notification->user_id === Auth::id()) {
            $notification->markAsRead();
            
            // If there's a link, redirect to it
            if ($notification->link) {
                return redirect($notification->link);
            }
        }
        
        return back();
    }

    /**
     * Delete a notification.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        
        if ($notification->user_id === Auth::id()) {
            $notification->delete();
            return back()->with('success', 'Notification deleted');
        }
        
        return back()->with('error', 'You do not have permission to delete this notification');
    }

    /**
     * Delete all read notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyRead()
    {
        $userId = Auth::id();
        
        $count = Notification::where('user_id', $userId)
            ->where('is_read', true)
            ->delete();
            
        return back()->with('success', $count . ' read notifications deleted');
    }

    /**
     * Show a specific notification.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to view this notification');
        }
        
        // Mark as read when viewed
        if (!$notification->is_read) {
            $notification->markAsRead();
        }
        
        return view('notifications.show', compact('notification'));
    }
}