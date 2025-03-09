{{-- resources/views/emails/notification.blade.php --}}
@component('mail::message')
# {{ $title }}

{{ $content }}

@if($link)
@component('mail::button', ['url' => $link, 'color' => $type === 'error' ? 'red' : ($type === 'warning' ? 'yellow' : 'blue')])
View Details
@endcomponent
@endif

@if($sourceType)
**Source**: {{ ucfirst(str_replace('_', ' ', $sourceType)) }}
@endif

**Time**: {{ $timestamp->format('F j, Y \a\t g:i A') }}

Thanks,<br>
{{ config('app.name') }}

@component('mail::subcopy')
You're receiving this email because you've enabled email notifications in your preferences.
[Manage your notification settings]({{ route('notifications.settings') }})
@endcomponent
@endcomponent