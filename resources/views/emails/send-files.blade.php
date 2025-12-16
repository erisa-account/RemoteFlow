{{-- <x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} 
</x-mail::message> --}}



@component('mail::message') 


{{ $description }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent