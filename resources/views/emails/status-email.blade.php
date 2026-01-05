@component('mail::message')

Hello {{ $userName }}

Your **{{ $type }}** leave request from {{ $startDate }} to {{ $endDate }}
has been **{{ ucfirst($status) }}**.

Send by: {{ $approverName }}





@component('mail::button', ['url' => route('user.userdashboard')])
Go to Dashboard
@endcomponent

@endcomponent