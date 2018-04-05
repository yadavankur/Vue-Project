@component('mail::message')
# {{ $content['title'] }}

{{ $content['body'] }}

@component('mail::table')
| Laravel       | Table         | Example  |
| ------------- |:-------------:| --------:|
| PHP           | Centered      | $100     |
| site-measure       | Right-Aligned | $200     |
@endcomponent

@component('mail::button', ['url' => ''])
{{ $content['button'] }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
