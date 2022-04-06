@component('mail::message')
{{ $message }}

@component('mail::button', ['url' => config('app.url')])
    Go to {{ config('app.name') }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent