<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'link',
        'type', // 'info', 'success', 'warning', 'error'
        'is_read',
        'importance', // 1-5 scale (5 being most important)
        'source_type', // e.g., 'courrier', 'annotation', 'draft'
        'source_id',   // ID of the related record
        'created_by',  // User who triggered the notification
        'metadata'     // JSON field for additional data
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'importance' => 'integer',
        'metadata' => 'array'
    ];

    /**
     * The user this notification belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The user who created/triggered this notification
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Polymorphic relationship to the source
     */
    public function source()
    {
        if ($this->source_type === 'courrier') {
            return $this->belongsTo(CourriersEntrants::class, 'source_id');
        } elseif ($this->source_type === 'annotation') {
            return $this->belongsTo(CourrierAnnotation::class, 'source_id');
        } elseif ($this->source_type === 'draft') {
            return $this->belongsTo(LecteurResponseDraft::class, 'source_id');
        } elseif ($this->source_type === 'courrier-sortant') {
            return $this->belongsTo(CourrierSortant::class, 'source_id');
        }
        
        return null;
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread(Builder $query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for high importance notifications
     */
    public function scopeHighImportance(Builder $query, $minLevel = 4)
    {
        return $query->where('importance', '>=', $minLevel);
    }

    /**
     * Mark this notification as read
     */
    public function markAsRead()
    {
        $this->is_read = true;
        $this->save();
        
        return $this;
    }

    /**
     * Get a color class based on notification type
     */
    public function getColorClass()
    {
        switch ($this->type) {
            case 'success':
                return 'bg-green-100 text-green-800 border-green-200';
            case 'warning':
                return 'bg-yellow-100 text-yellow-800 border-yellow-200';
            case 'error':
                return 'bg-red-100 text-red-800 border-red-200';
            default: // info
                return 'bg-blue-100 text-blue-800 border-blue-200';
        }
    }

    /**
     * Get an icon based on notification type and source
     */
    public function getIcon()
    {
        // Source-based icons
        if ($this->source_type === 'courrier') {
            return '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>';
        } elseif ($this->source_type === 'annotation') {
            return '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
            </svg>';
        } elseif ($this->source_type === 'draft') {
            return '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>';
        } elseif ($this->source_type === 'courrier-sortant') {
            return '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
            </svg>';
        }

        // Type-based icons (fallback)
        switch ($this->type) {
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
}