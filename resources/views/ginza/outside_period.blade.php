<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        @vite(['resources/scss/application.scss'])
    </x-slot>

    <div class="py-16">
        <div class="form-area">

            @include('ginza.hero')

            <div class="text-center mb-4">
                8/22～8/25は混雑状況によって整理券を配布する場合があります
            </div>

            <div class="content-area text-center">
                本イベントのお問い合わせは下記までお願いします。<br>
                {{ config('app.mail_secretariat') }}
            </div>

        </div>
    </div>

</x-application-layout>
