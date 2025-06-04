<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        @vite(['resources/scss/application.scss'])
        <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script> <!-- 住所入力 -->
    </x-slot>

    <div class="p-2 sm:p-6">
        <img src="{{ asset('image/LOGO.png') }}"
             style="width: 300px; margin: 5rem auto 0;" alt="ロゴ">

        <div class="event-title">
        ETRO per Kaito Takahashi<br>
        渋⾕PARCO 1階ポップアップスペースGATE<br>
        6月27日（金）入場抽選応募フォーム
        </div>

        <div class="support-area">
            <div class="support-title">＜ 抽選応募期間 ＞</div>
            6⽉20⽇(⾦)正午12:00〜6/23(⽉)23:59＊受付終了<br>
        </div>

        <div class="support-area">
            <div class="support-title">＜ 抽選応募 優先対象 ＞</div>
            本抽選は、PARCOカードをお持ちのお客さま、及び下記URLより、<br>
            新規PARCOカード(*)にお申込みを完了されたお客さまを優先とさせていただきます。<br>
            ＊新規ご入会の場合、下記よりお申込みいただいた方のみが対象となります。<br>
        </div>

        <div class="form-area" style="text-align: center;font-size: 1.2rem;">
            @include('precautions')
        </div>

        <div class="support-area">
            イベントのお問い合わせは、下記までお願いします。<br>
            2営業日以内に担当からご連絡します。<br>
            新規PARCOカードのお申し込みについてのお問い合わせ先ではございません。<br>
            info@etro-shibuya-parco-cp.com
        </div>

    </div>
</x-application-layout>

<style>
</style>
