@component('mail::message')


# 👋 Hello {{ $userName }}

We noticed that you haven't checked in today.

Your status was automatically set to **On Site**.

Please review it if needed.

---

@component('mail::button', ['url' => route('user.userdashboard')])
Go to Dashboard
@endcomponent

@endcomponent