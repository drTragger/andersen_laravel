@component('mail::message')
    # {{ $data['subject'] }}

    {{ ucfirst($data['username']) }},

    Your account was deleted

    Thanks,
    Misha from Andersen
@endcomponent
