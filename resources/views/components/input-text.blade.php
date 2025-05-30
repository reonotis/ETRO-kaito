@props([
    'disabled' => false,
    'error' => false
])

<input

    @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm' .
                   ($error ? ' border-red-500 bg-red-50' : '')
    ]) }}

>
