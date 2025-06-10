<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        @vite(['resources/scss/application.scss'])
        <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script> <!-- 住所入力 -->
    </x-slot>

    <div class="p-2 sm:p-6">

        <div class="event-title">
        ETRO per Kaito Takahashi<br>
        渋⾕PARCO 1階ポップアップスペースGATE<br>
        6月27日（金）入場抽選応募フォーム
        </div>

        <div class="support-area">
            <div class="support-title">＜ 抽選応募期間 ＞</div>
            6⽉20⽇(⾦)正午12:00〜6/23(⽉)23:59＊受付終了<br>
        </div>


        <div class="form-area precautions">
            @include('precautions')
        </div>

        <div class="support-area">
            イベントのお問い合わせは、下記までお願いします。<br>
            2営業日以内に担当からご連絡します。<br>
            info@etro-shibuya-parco-cp.com
        </div>

    </div>
</x-application-layout>

<style>
</style>
