<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        @vite(['resources/scss/application.scss'])
    </x-slot>

    <div class="p-2 sm:p-6">
        <div class="ticket-container">
            <div class="event-title">ETRO×高橋海人カプセルコレクション 渋谷パルコ当選チケット</div>
            <div class="content-area">有効なチケットです</div>
            <div class="content-area">
                <div class="user-info">{{ $application->sei . ' ' . $application->mei }} 様</div>
                <div class="unique-code">管理番号： {{ $application->unique_code }}</div>
            </div>
            <div class="content-area">来場可能です</div>
        </div>
    </div>
</x-application-layout>
