@props(['disabled' => false])

@php
$hasError = $errors->has($attributes->get('name'));
$baseClasses = 'form-control';

$classes = $baseClasses . ($hasError ? ' is-invalid' : '');
@endphp

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classes]) !!}>
