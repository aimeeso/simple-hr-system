@component('mails.themes.layout')
{{-- Header --}}
@slot('header')
@component('mails.themes.header')
@endcomponent
@endslot

{{-- Logo --}}
@slot('logo')
@component('mails.themes.logo')
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mails.themes.subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mails.themes.footer')
{{-- © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.') --}}
@endcomponent
@endslot
@endcomponent