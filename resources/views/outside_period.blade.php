<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        <link rel="stylesheet" href="{{ asset('application.css') }}">
    </x-slot>

    <div class="logo-container">
        <img src="{{ asset('image/TWW_logo.png') }}" alt="TWW ロゴ">
    </div>


        <div class="support-area">
            応募は終了しました
        </div>

        <div class="form-area precautions">
            @include('precautions')
        </div>

        <div class="support-area">
            入場抽選のお問い合わせは、下記までお願いします。<br>
            2営業日以内に担当からご連絡します。<br>
        </div>

    </div>
</x-application-layout>

<style>
</style>
