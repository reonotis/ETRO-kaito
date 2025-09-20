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
            <div class="content-area">有効なチケットです</div>
            <div class="content-area">
                <div class="user-info">{{ $application->name }} 様</div>
                <div class="unique-code">管理番号： {{ $application->unique_code }}</div>
            </div>
            <div class="content-area">来場可能です</div>
        </div>
    </div>
</x-application-layout>
