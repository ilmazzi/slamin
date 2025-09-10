@props([
    'name' => 'circle',
    'size' => '24',
    'class' => '',
    'fallback' => true
])

@php
use App\Helpers\IconHelper;
$iconHtml = IconHelper::getIconHtml($name, $size, $class, $fallback);
@endphp

{!! $iconHtml !!}
