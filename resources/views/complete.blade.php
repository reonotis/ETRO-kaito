<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        <link rel="stylesheet" href="{{ asset('application.css') }}?v={{ time() }}">
    </x-slot>

    <div class="ticket-page-container">
        <div class="logo-container">
            <img src="{{ asset('image/TWW_logo.png') }}" alt="TWW ロゴ">
        </div>

        <div class="ticket-container">
            <div class="content-area">
                申し込みが完了しました。<br>
                ご入力していただいたメールアドレスにメールを送信しておりますのでご確認ください。
            </div>
        </div>
    </div>
</x-application-layout>
