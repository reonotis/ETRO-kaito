<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        @vite(['resources/scss/application.scss'])

        <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script> <!-- 住所入力 -->
    </x-slot>

    <div class="p-2 sm:p-6">
        <div class="event-title">
            ETRO per Kaito Takahashi<br>
            銀座ポップアップ<br>
            7⽉8⽇（火）入場抽選応募フォーム
        </div>
        <div class="form-area">
            申し込みが完了しました。<br>
            ご入力していただいたメールアドレスにメールを送信しておりますのでご確認ください。
        </div>
    </div>
</x-application-layout>

<style>
</style>
