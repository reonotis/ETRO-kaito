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
            6⽉28⽇（⼟）/6⽉29⽇（⽇）入場抽選応募フォーム
        </div>

        <div class="support-area">
            <div class="support-title">【お詫びと申込期間変更のお知らせ】</div>
                このたび、⼀部の期間において応募サイトが正しく表⽰されない不具合が発⽣しておりました。<br>
                ご不便・ご迷惑をおかけしましたこと、深くお詫び申し上げます。<br>
                つきましては、対象期間中にアクセスできなかったお客様にもご応募いただけるよう、申込期間を以下のとおり変更させていただきます。<br>
        </div>

        <div class="support-area">
            <div class="support-title">＜変更後の申込期間＞</div>
            <span style="color: red;">6⽉26⽇00:00 〜6⽉27⽇AM9:00</span><br>
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
