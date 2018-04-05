@component('mail::message')

{!! $content['body'] !!}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
