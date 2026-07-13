<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        @vite(['resources/scss/application.scss'])
    </x-slot>

    <div class="py-16">
        <div class="form-area">

            @include('hankyu.hero')

            <div class="content-area text-center">現在準備中です。</div>

        </div>
    </div>

</x-application-layout>
