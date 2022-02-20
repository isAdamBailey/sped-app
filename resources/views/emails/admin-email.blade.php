@component('mail::message')
# Site Alert

The following message was sent by {{ config('app.name') }}:

{{ $message }}

@component('mail::button', ['url' => config('app.url')])
Go To {{ config('app.name') }}
@endcomponent

@endcomponent
