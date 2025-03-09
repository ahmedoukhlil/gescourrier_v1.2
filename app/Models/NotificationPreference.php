<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_notifications', // Boolean - receive email notifications
        'browser_notifications', // Boolean - receive browser notifications
        'notification_types', // JSON array of enabled notification types
        'minimum_importance', // Minimum importance level to receive notifications (1-5)
        'daily_digest', // Boolean - receive daily digest email
        'daily_digest_time', // Time to receive daily digest (e.g., "09:00")
        'pause_until', // Datetime - pause notifications until this time
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'browser_notifications' => 'boolean',
        'notification_types' => 'array',
        'minimum_importance' => 'integer',
        'daily_digest' => 'boolean',
        'pause_until' => 'datetime',
    ];

    /**
     * The user these preferences belong to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if a specific notification type is enabled
     *
     * @param string $type Notification type (e.g., 'new_mail', 'annotation', 'draft_feedback')
     * @return bool
     */
    public function isTypeEnabled($type)
    {
        return in_array($type, $this->notification_types ?? []);
    }

    /**
     * Check if notifications are currently paused
     *
     * @return bool
     */
    public function isPaused()
    {
        return $this->pause_until && $this->pause_until > now();
    }

    /**
     * Check if the user should receive a notification based on importance
     *
     * @param int $importance Importance level (1-5)
     * @return bool
     */
    public function shouldReceiveByImportance($importance)
    {
        return $importance >= ($this->minimum_importance ?? 1);
    }

    /**
     * Get default preferences for a new user
     *
     * @return array
     */
    public static function getDefaults()
    {
        return [
            'email_notifications' => true,
            'browser_notifications' => true,
            'notification_types' => [
                'new_mail',
                'mail_shared',
                'annotation_added',
                'draft_submitted',
                'draft_feedback',
                'urgent_mail',
            ],
            'minimum_importance' => 1, // Receive all notifications by default
            'daily_digest' => false,
            'daily_digest_time' => '09:00',
            'pause_until' => null,
        ];
    }
}