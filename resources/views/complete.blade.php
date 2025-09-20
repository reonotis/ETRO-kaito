<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        <link rel="stylesheet" href="{{ asset('application.css') }}">
    </x-slot>

    <div class="logo-container">
        <img src="{{ asset('image/TWW_logo.png') }}" alt="TWW ロゴ">
    </div>

    <div class="p-2 sm:p-6">
        <div class="form-area">
            申し込みが完了しました。<br>
            ご入力していただいたメールアドレスにメールを送信しておりますのでご確認ください。
        </div>
    </div>
</x-application-layout>

<style>
</style>
