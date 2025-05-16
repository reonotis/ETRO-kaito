<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        @vite(['resources/scss/application.scss'])
        <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script> <!-- 住所入力 -->
    </x-slot>

    <div class="p-6">
        <div class="event-title">ETRO×高橋海人カプセルコレクション 渋谷パルコ当選チケット</div>

        <div class="form-area">
            <form action="" method="post" >
                @csrf

                <div class="contents-row">
                    <div class="content-title">お名前</div>
                    <div class="content-items">
                        <span class="support-msg">※当日お持ちいただく身分証明書と同じ名前を入力して下さい。</span>
                        <div class="flex" style="gap: .5rem;">
                            <x-input-text id="sei" class="w-full" type="text" name="sei" :value="old('sei')"
                                          :error="$errors->has('sei')"
                                          placeholder="田中" />
                            <x-input-text id="mei" class="w-full" type="text" name="mei" :value="old('mei')"
                                          :error="$errors->has('mei')"
                                          placeholder="太郎" />
                        </div>
                        <x-input-error :messages="$errors->get('sei')" class="mb-1" />
                        <x-input-error :messages="$errors->get('mei')" class="mb-1" />
                    </div>
                </div>
                <div class="contents-row">
                    <div class="content-title">ヨミ</div>
                    <div class="content-items">
                        <div class="flex" style="gap: .5rem;">
                            <x-input-text id="sei_kana" class="w-full" type="text" name="sei_kana"
                                          :value="old('sei_kana')" placeholder="タナカ"
                                          :error="$errors->has('sei_kana')"
                                          required/>
                            <x-input-text id="mei_kana" class="w-full" type="text" name="mei_kana"
                                          :value="old('mei_kana')" placeholder="タロウ"
                                          :error="$errors->has('mei_kana')"
                                          required/>
                        </div>
                        <x-input-error :messages="$errors->get('sei_kana')" class="" />
                        <x-input-error :messages="$errors->get('mei_kana')" class="mb-1" />
                    </div>
                </div>
                <div class="contents-row">
                    <div class="content-title">性別</div>
                    <div class="content-items">
                        <div class="flex" style="flex-wrap: wrap;gap: .5rem;">
                            @foreach(App\Consts\Common::SEX_LIST as $key => $value)
                                <label class="radio-label" style="padding: 0 .5rem;">
                                    <input type="radio" class="" name="sex" value="{{ $key }}"
                                           @if(old('sex') == $key)
                                               checked="checked"
                                        @endif
                                    >
                                    {{ $value }}
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('sex')" class="" />
                    </div>
                </div>
                <div class="contents-row">
                    <div class="content-title">年齢</div>
                    <div class="content-items">
                        <div class="flex items-center" style="gap: .5rem;">
                            <div style="width: 100px">
                                <x-input-text id="age" class="w-full" type="number" name="age" :value="old('age')" placeholder="18" required/>
                            </div>
                            歳
                        </div>
                        <x-input-error :messages="$errors->get('age')" class="mb-1" />
                    </div>
                </div>
                <div class="contents-row">
                    <div class="content-title">ご住所</div>
                    <div class="content-items">
                        <div class="flex items-center" style="width: 300px;gap: .5rem;" >
                            <x-input-text id="zip21" class="w-full" type="text" name="zip21" :value="old('zip21')"
                                          :error="$errors->has('zip21')"
                                          onKeyUp="AjaxZip3.zip2addr('zip21', 'zip22', 'pref21', 'address21', 'street21')"
                                          placeholder="100" required/>
                            -
                            <x-input-text id="zip22" class="w-full" type="text" name="zip22" :value="old('zip22')"
                                          :error="$errors->has('zip22')"
                                          onKeyUp="AjaxZip3.zip2addr('zip21', 'zip22', 'pref21', 'address21', 'street21')"
                                          placeholder="0001" required/>
                        </div>
                        <x-input-error :messages="$errors->get('zip21')" class="" />
                        <x-input-error :messages="$errors->get('zip22')" class="" />

                        <div class="flex items-center mt-1" style="gap: .5rem;" >
                            <div class="w-48">
                                <x-input-text id="pref21" class="w-full" type="text" name="pref21" :value="old('pref21')"
                                              :error="$errors->has('pref21')"
                                              placeholder="東京都" />
                            </div>
                            <div class="w-48">
                                <x-input-text id="address21" class="w-full" type="text" name="address21" :value="old('address21')"
                                              :error="$errors->has('address21')"
                                              placeholder="千代田区" />
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('pref21')" class="" />
                        <x-input-error :messages="$errors->get('address21')" class="" />

                        <x-input-text id="street21" class="w-full mt-1" type="text" name="street21" :value="old('street21')"
                                      :error="$errors->has('street21')"
                                      placeholder="神田神保町1-105" />
                        <x-input-error :messages="$errors->get('street21')" class="" />
                    </div>
                </div>
                <div class="contents-row">
                    <div class="content-title">電話番号</div>
                    <div class="content-items">
                        <span class="support-msg">※ハイフン(-)を入れて入力して下さい。</span>
                        <x-input-text id="tel" class="w-full" type="text" name="tel" :value="old('tel')"
                                      :error="$errors->has('tel')"
                                      placeholder="090-1234-5678" required/>
                        <x-input-error :messages="$errors->get('tel')" class="mb-1" />
                    </div>
                </div>
                <div class="contents-row">
                    <div class="content-title">メールアドレス</div>
                    <div class="content-items">
                        <span class="support-msg">
                            ※メールアドレスは確認用の為、2回入力してください<br>
                            ※docomo、au、softbank、iCloud等の各キャリアのメールアドレスは、ドメイン指定受信を設定されている可能性がありメールが正しく届かない事がある為、PC用のメールアドレスを記入する事をお勧め致します。
                        </span>
                        <div class="mb-2">
                            <x-input-text id="email" class="w-full" type="email" name="email" :value="old('email')"
                                          :error="$errors->has('email')"
                                          placeholder="sample@exsample.com" required/>
                        </div>
                        <x-input-text id="email_confirmation" class="w-full" type="email" name="email_confirmation"
                                      :error="$errors->has('email_confirmation')"
                                      :value="old('email_confirmation')" placeholder="sample@exsample.com" required/>
                        <x-input-error :messages="$errors->get('email')" class="mb-1" />
                        <x-input-error :messages="$errors->get('email_confirmation')" class="mb-1" />
                    </div>
                </div>
                <div class="contents-row">
                    <div class="content-title">利用規約</div>
                    <div class="content-items">
                        <div class="terms-condition">
                            @include('terms')
                        </div>
                        <div class="flex justify-center my-1">
                            <label><input type="checkbox" name="term" @if(old('term')) checked="checked" @endif>同意する</label>
                        </div>
                        <x-input-error :messages="$errors->get('term')" class="" />
                    </div>
                </div>
                <div class="contents-row">
                    <div class="content-title">お問い合わせ</div>
                    <div class="content-items">
                        本イベントのお問い合わせは下記のメールアドレスまでご連絡下さい。<br>
                        2営業日以内に担当者からご連絡いたします<br>
                        info@domain.com
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
