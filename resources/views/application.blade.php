<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        <link rel="stylesheet" href="{{ asset('application.css') }}">
        <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script> <!-- 住所入力 -->
    </x-slot>

    <div class="logo-container">
        <img src="{{ asset('image/TWW_logo.png') }}" alt="TWW ロゴ">
    </div>

    <div class="p-2 sm:p-6">

        <div class="form-area">
            <form action="" method="post" >
                @csrf

                <div class="contents-row">
                    <div class="content-title">名前 (Name)</div>
                    <div class="content-items">
                        <span class="support-msg">Please enter the same name as on your ID for check-in on the day of the event.</span>
                        <x-input-text id="name" class="w-full" type="text" name="name" :value="old('name')"
                                        :error="$errors->has('name')"
                                        placeholder="田中 太郎" />
                        <x-input-error :messages="$errors->get('name')" class="mb-1" />
                    </div>
                </div>
                <div class="contents-row">
                    <div class="content-title">居住国 (Country of Residence)</div>
                    <div class="content-items">
                        <x-input-text id="address" class="w-full" type="text" name="address" :value="old('address')"
                                        :error="$errors->has('address')"
                                        placeholder="日本 東京都 新宿区" />
                        <x-input-error :messages="$errors->get('address')" class="mb-1" />
                    </div>
                </div>
                <div class="contents-row">
                    <div class="content-title">電話番号 (Telephone Number)</div>
                    <div class="content-items">
                        <span class="support-msg">Please include your country code (+) and hyphen (-) for the number. (+1 000-000-0000)</span>
                        <x-input-text id="tel" class="w-full" type="text" name="tel" :value="old('tel')"
                                    :error="$errors->has('tel')"
                                    placeholder="090-1234-5678" />
                        <x-input-error :messages="$errors->get('tel')" class="mb-1" />
                    </div>
                </div>

                <div class="contents-row">
                    <div class="content-title">メールアドレス (Email Address)</div>
                    <div class="content-items">
                        <span class="support-msg">
                            ※Please enter the same email address twice for confirmation. Since domain-restricted email address (i.e. docomo, au, softbank, and iCloud) may sometimes block delivery, we recommend entering another email address to ensure proper receipt.<br>
                        </span>
                        <div class="mb-2">
                            <x-input-text id="email" class="w-full" type="email" name="email" :value="old('email')"
                                        :error="$errors->has('email')"
                                        placeholder="sample@exsample.com" />
                        </div>
                        <x-input-text id="email_confirmation" class="w-full" type="email" name="email_confirmation"
                                    :error="$errors->has('email_confirmation')"
                                    :value="old('email_confirmation')" placeholder="sample@exsample.com" />
                        <x-input-error :messages="$errors->get('email')" class="mb-1" />
                        <x-input-error :messages="$errors->get('email_confirmation')" class="mb-1" />
                    </div>
                </div>

                <div class="contents-row">
                    <div class="content-title">希望イベント (Preferred Events)</div>
                    <div class="content-items">
                        <div class="flex" style="flex-wrap: wrap;gap: .5rem;">
                            @foreach(App\Consts\Common::TARGET_DATE_LIST as $key => $value)
                                <label class="radio-label" style="padding: 0 .5rem;">
                                    <input type="checkbox" class="" name="target_date[]" value="{{ $key }}"
                                        @if(old('target_date') == $key)
                                            checked="checked"
                                        @endif
                                    >
                                    {{ $value }}
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('target_date')" class="mb-1" />
                    </div>
                </div>


                <div class="flex justify-center">
                    <input type="submit" class="submit-btn" value="申し込む">
                </div>
            </form>
        </div>
    </div>
</x-application-layout>

<style>
</style>
