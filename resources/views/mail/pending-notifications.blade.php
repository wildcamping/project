<x-mail::message>
# {{ __('Hello') }}, {{ $user->name }}!

{{ __('We've noticed you have') }} {{ $pendingNotificationsCount }} {{ Str::plural('notification', $pendingNotificationsCount) }}. {{ __('You can view notifications by clicking the button below.') }}

<x-mail::button :url="route('notifications.index')">
{{ __('View Notifications') }}
</x-mail::button>

{{ __('If you no longer wish to receive these emails, you can change your "Mail Preference Time" in your [profile settings]') }}({{ route('profile.edit') }}).

{{ __('See you soon,') }}<br>
{{ config('app.name') }}

</x-mail::message>
