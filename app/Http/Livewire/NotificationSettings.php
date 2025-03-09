<?php

namespace App\Http\Livewire;

use App\Models\NotificationPreference;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationSettings extends Component
{
    public $emailNotifications = true;
    public $browserNotifications = true;
    public $notificationTypes = [];
    public $minimumImportance = 1;
    public $dailyDigest = false;
    public $dailyDigestTime = '09:00';
    public $pauseUntil = null;
    public $pauseDuration = null;
    
    // Available notification types with descriptions
    public $availableTypes = [
        'new_mail' => 'New incoming mail',
        'mail_shared' => 'Mail shared with me',
        'annotation_added' => 'New annotations',
        'draft_submitted' => 'Response draft submissions',
        'draft_feedback' => 'Feedback on response drafts',
        'urgent_mail' => 'Urgent mail notifications',
        'decharge_missing' => 'Missing receipt acknowledgments',
        'courrier_status_change' => 'Mail status changes'
    ];
    
    // Pause duration options
    public $pauseDurationOptions = [
        '1h' => '1 hour',
        '2h' => '2 hours',
        '4h' => '4 hours',
        '8h' => '8 hours',
        '1d' => '1 day',
        '2d' => '2 days',
        '1w' => '1 week',
        'custom' => 'Custom duration'
    ];
    
    // For custom pause duration
    public $customPauseDate = null;
    public $customPauseTime = null;
    public $showCustomPause = false;
    
    // For importance slider
    public $importanceLevels = [
        1 => 'All notifications',
        2 => 'Low importance and above',
        3 => 'Medium importance and above',
        4 => 'High importance only',
        5 => 'Critical notifications only'
    ];
    
    public function mount()
    {
        $user = Auth::user();
        $preferences = $user->notificationPreferences;
        
        if (!$preferences) {
            // Create default preferences if none exist
            $defaults = NotificationPreference::getDefaults();
            $preferences = $user->notificationPreferences()->create($defaults);
        }
        
        // Set component properties from the preferences
        $this->emailNotifications = $preferences->email_notifications;
        $this->browserNotifications = $preferences->browser_notifications;
        $this->notificationTypes = $preferences->notification_types ?? array_keys($this->availableTypes);
        $this->minimumImportance = $preferences->minimum_importance;
        $this->dailyDigest = $preferences->daily_digest;
        $this->dailyDigestTime = $preferences->daily_digest_time;
        
        // Handle pause settings
        if ($preferences->pause_until && $preferences->pause_until > now()) {
            $this->pauseUntil = $preferences->pause_until;
            $this->customPauseDate = $preferences->pause_until->format('Y-m-d');
            $this->customPauseTime = $preferences->pause_until->format('H:i');
            $this->pauseDuration = 'custom';
            $this->showCustomPause = true;
        }
    }
    
    /**
     * Update the pause duration based on selection
     */
    public function updatedPauseDuration()
    {
        if ($this->pauseDuration === 'custom') {
            $this->showCustomPause = true;
            return;
        }
        
        $this->showCustomPause = false;
        
        // Calculate pause until time based on selection
        if ($this->pauseDuration) {
            $now = Carbon::now();
            
            switch ($this->pauseDuration) {
                case '1h':
                    $this->pauseUntil = $now->copy()->addHour();
                    break;
                case '2h':
                    $this->pauseUntil = $now->copy()->addHours(2);
                    break;
                case '4h':
                    $this->pauseUntil = $now->copy()->addHours(4);
                    break;
                case '8h':
                    $this->pauseUntil = $now->copy()->addHours(8);
                    break;
                case '1d':
                    $this->pauseUntil = $now->copy()->addDay();
                    break;
                case '2d':
                    $this->pauseUntil = $now->copy()->addDays(2);
                    break;
                case '1w':
                    $this->pauseUntil = $now->copy()->addWeek();
                    break;
                default:
                    $this->pauseUntil = null;
            }
        } else {
            $this->pauseUntil = null;
        }
    }
    
    /**
     * Update custom pause datetime when date or time changes
     */
    public function updatedCustomPauseDate()
    {
        $this->updateCustomPause();
    }
    
    public function updatedCustomPauseTime()
    {
        $this->updateCustomPause();
    }
    
    /**
     * Update the pauseUntil property based on custom date/time
     */
    private function updateCustomPause()
    {
        if ($this->customPauseDate && $this->customPauseTime) {
            try {
                $this->pauseUntil = Carbon::parse($this->customPauseDate . ' ' . $this->customPauseTime);
            } catch (\Exception $e) {
                // Invalid date/time format
                $this->pauseUntil = null;
            }
        } else {
            $this->pauseUntil = null;
        }
    }
    
    /**
     * Resume notifications (clear pause)
     */
    public function resumeNotifications()
    {
        $this->pauseUntil = null;
        $this->pauseDuration = null;
        $this->showCustomPause = false;
        $this->customPauseDate = null;
        $this->customPauseTime = null;
        
        $this->save();
    }
    
    /**
     * Save notification preferences
     */
    public function save()
    {
        $user = Auth::user();
        
        $user->notificationPreferences()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'email_notifications' => $this->emailNotifications,
                'browser_notifications' => $this->browserNotifications,
                'notification_types' => $this->notificationTypes,
                'minimum_importance' => $this->minimumImportance,
                'daily_digest' => $this->dailyDigest,
                'daily_digest_time' => $this->dailyDigestTime,
                'pause_until' => $this->pauseUntil,
            ]
        );
        
        $this->dispatchBrowserEvent('preferences-saved');
        session()->flash('message', 'Notification preferences saved successfully.');
    }
    
    public function render()
    {
        return view('livewire.notification-settings');
    }
}