@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border border-gray-300 bg-gray-100 p-1 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}>
