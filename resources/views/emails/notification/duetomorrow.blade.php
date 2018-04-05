@component('mail::message')
# Sales order No: {{  $content['orderId'] }}

The activity of [{{ $content['activityName'] }}] is due tomorrow ({{$content['dueDay']}}).
Please remember to complete it by tomorrow,
otherwise the delivery of this order will be delayed.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
