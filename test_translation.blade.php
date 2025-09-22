{{-- Test Blade Directives --}}
<h1>Test Traduzioni Miste</h1>

<p>@t('admin.dashboard')</p>
<p>@auto('Testo hardcoded per cattura')</p>
<p>@trans('common.welcome')</p>

{{-- Test con parametri --}}
<p>@t('admin.translation_management', [], 'en')</p>



