<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        @vite(['resources/scss/application.scss'])
    </x-slot>

    <div class="p-2 sm:p-6">
        <div class="ticket-container">
            <div class="event-title">ETRO×高橋海人カプセルコレクション 渋谷パルコ当選チケット</div>
            <div class="content-area">{{ $message }}</div>
        </div>
    </div>
</x-application-layout>
