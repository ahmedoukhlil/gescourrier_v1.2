{{-- resources/views/emails/daily-digest.blade.php --}}
@component('mail::message')
# Your Daily Notification Digest

Hello {{ $user->name }},

Here's a summary of your notifications from the past 24 hours ({{ $date }}).

## Summary
* **Total notifications**: {{ $stats['total'] }}
@if($stats['highImportance'] > 0)
* **High importance notifications**: {{ $stats['highImportance'] }}
@endif

@component('mail::table')
| Type | Count |
|------|-------|
@foreach($stats['byType'] as $type => $count)
| {{ ucfirst($type) }} | {{ $count }} |
@endforeach
@endcomponent

@if(count($stats['bySource']) > 0)
@component('mail::table')
| Source | Count |
|--------|-------|
@foreach($stats['bySource'] as $source => $count)
| {{ $source ? ucfirst(str_replace('_', ' ', $source)) : 'General' }} | {{ $count }} |
@endforeach
@endcomponent
@endif

## Recent Notifications

@foreach($notifications->take(10) as $notification)
@component('mail::panel')
### {{ $notification->title }}
{{ $notification->content }}

@if($notification->importance >= 4)
**Importance**: High
@endif

@if($notification->link)
[View Details]({{ $notification->link }})
@endif

{{ $notification->created_at->format('M j, g:i A') }}
@endcomponent
@endforeach

@if($notifications->count() > 10)
Plus {{ $notifications->count() - 10 }} more notifications...
@endif

@component('mail::button', ['url' => route('notifications.index')])
View All Notifications
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@component('mail::subcopy')
You're receiving this daily digest because you've enabled it in your notification preferences.
[Manage your notification settings]({{ route('notifications.settings') }})
@endcomponent
@endcomponent