<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        @vite(['resources/scss/application.scss'])
    </x-slot>

    <div class="py-16">
        <div class="form-area">

            @include('ginza.hero')

            <div class="content-area text-center">
                本イベントのお問い合わせは下記までお願いします。<br>
                {{ config('app.mail_secretariat') }}
            </div>

        </div>
    </div>

</x-application-layout>
