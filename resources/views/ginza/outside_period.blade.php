<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        @vite(['resources/scss/application.scss'])
    </x-slot>

    <div class="py-16">
        <div class="form-area">

            @include('ginza.hero')

        </div>
    </div>

</x-application-layout>
