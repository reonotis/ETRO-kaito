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
            入場抽選応募フォーム
        </div>
        <div class="form-area">
            申し込みが完了しました。<br>
            ご入力していただいたメールアドレスにメールを送信しておりますのでご確認ください。<br>
            <br>
            ※自動返信メールが迷惑メールフォルダに自動的に振り分けられる場合がございます。メールが届かない場合は迷惑メールフォルダもご確認いただきますようお願いいたします。<br>
        </div>
    </div>
</x-application-layout>

<style>
</style>
