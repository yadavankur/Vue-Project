@component('mail::message')
# Sales order No: {{  $content['orderId'] }}

The activity of [{{ $content['activityName'] }}] was due on {{ $content['dueDay'] }}.
Further action needs to be taken immediately.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
