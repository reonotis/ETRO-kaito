<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        @vite(['resources/scss/application.scss'])
        <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script> <!-- 住所入力 -->
    </x-slot>

    <div class="p-2 sm:p-6">

        <div class="event-title">
            ETRO per Kaito Takahashi for holiday<br>
            11月26日〜28日販売イベント<br>
            入場抽選応募フォーム<br>
        </div>

        <div class="common-text">11/29（土）以降は一般販売とします。但し当日の混雑状況に応じ整理券を配布する場合があります。</div>

        <div class="support-area">
            <div class="support-title">＜申込期間＞</div>
            抽選応募は終了しました
        </div>

        <div class="form-area precautions">
            @include('precautions')
        </div>

        <div class="support-area">
            入場抽選のお問い合わせは、下記までお願いします。<br>
            2営業日以内に担当からご連絡します。<br>
            info@etro-ginza-lottery.com
        </div>

    </div>
</x-application-layout>

<style>
</style>
