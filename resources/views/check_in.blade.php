<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        @vite(['resources/scss/application.scss'])
    </x-slot>

    <div class="p-2 sm:p-6">
        <div class="ticket-container">
            <div class="event-title">
                来店予約抽選応募フォーム<br>
                -11月26日～28日エトロ銀座本店-<br>
            </div>

            <div class="common-text">11月29日(土)以降は通常販売となり、来店予約抽選はございません。<br>但し、当日の混雑状況に応じ、入場整理券を配布する場合がございます。。</div>

            <div class="content-area">有効なチケットです</div>
            <div class="content-area">
                <div class="user-info">{{ $application->sei . ' ' . $application->mei }} 様</div>
                <div class="unique-code">管理番号： {{ $application->unique_code }}</div>
                <div class="unique-code">来場予定日時： {{ $section_name }}</div>
                <div class="unique-code">{{ $application->choice_4 }}</div>
            </div>
            <div class="content-area">来場可能です</div>
        </div>
    </div>
</x-application-layout>
