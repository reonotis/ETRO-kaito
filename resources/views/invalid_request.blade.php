<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        @vite(['resources/scss/application.scss'])
    </x-slot>

    <div class="p-2 sm:p-6">
        <div class="ticket-container">
            <div class="event-title">
                ETRO per Kaito Takahashi<br>
                渋⾕PARCO 1階ポップアップ<br>
                6月27日（金）入場抽選応募フォーム
            </div>
            <div class="content-area">{{ $message }}</div>
        </div>
    </div>
</x-application-layout>
