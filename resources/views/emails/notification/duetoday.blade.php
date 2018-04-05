@component('mail::message')
# Sales order No: {{  $content['orderId'] }}

The activity of [{{ $content['activityName'] }}] is due today ({{$content['dueDay']}}).
Please check and complete this activity immediately,
otherwise the delivery of this order will be delayed.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

