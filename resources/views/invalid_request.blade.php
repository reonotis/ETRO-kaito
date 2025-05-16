<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        @vite(['resources/scss/application.scss'])
    </x-slot>

    <div class="p-6">
        <div class="ticket-container">
            <div class="event-title">ETRO×高橋海人カプセルコレクション 渋谷パルコ当選チケット</div>
            <div class="content-area">{{ $message }}</div>
        </div>
    </div>
</x-application-layout>

<style>
    .ticket-container {
        max-width: 600px;
        width: 100%;
        margin: 0 auto;
        padding: 20px 30px;
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .event-title {
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 10px;
    }

    .content-area {
        text-align: center;
        color: red;
        font-size: 1.2rem;
        font-weight: bold;
    }

</style>
