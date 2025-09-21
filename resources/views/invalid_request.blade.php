<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        <link rel="stylesheet" href="{{ asset('application.css') }}">
    </x-slot>

    <div class="logo-container">
        <img src="{{ asset('image/TWW_logo.png') }}" alt="TWW ロゴ">
    </div>

    <div class="p-2 sm:p-6">
        <div class="ticket-container">
            <div class="content-area">{{ $message }}</div>
        </div>
    </div>
</x-application-layout>
