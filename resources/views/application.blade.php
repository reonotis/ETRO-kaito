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

        <div class="form-area" style="text-align: center;font-size: 1.2rem; margin-bottom: 2rem;">
            @include('precautions')
        </div>

        <div class="form-area">
            <form action="" method="post" >
                @csrf

                <div class="contents-row">
                    <div class="content-title">PARCOカード</div>
                    <div class="content-items">
                        <span class="support-msg">PARCOカード お客様番号、または新規申込完了メールに記載の「申し込み番号」を入力して下さい</span>
                        <div class="flex" style="gap: .5rem;">
                            <x-input-text id="sei" class="w-full" type="text" name="card_no" :value="old('card_no')"
                                          :error="$errors->has('card_no')"
                                          placeholder="PARCOカードナンバー" />
                        </div>
                    </div>
                </div>
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
                    <div class="content-title">ご⼊場に関するお願い事項</div>
                    <div class="content-items">
                        ・ご⼊場されるお客さまは、以下1〜3までを事前にご準備ください。<br>
                        1、当⽇ご本⼈確認のための顔写真付き⾝分証明書(運転免許証、パスポート、マイナンバーカード、特別永住者証明書など）<br>
                        2、PARCOカードまたはPARCOカードお申込み完了メール<br>
                        3、⼊場チケット画⾯が表⽰できるお客さまのスマートフォンをご持参ください。お並びになる前に⼊場チケット表⽰のご準備をお願いします。<br>
                        ・有効期限切れの本⼈確認証・そのコピー・写真でのご提⽰、顔写真が付いていない証明書複数枚のご提⽰は不可となります。予めご了承ください。<br>
                        ・ご案内時間の5分前までに指定の場所までお越しください。ご案内時間に遅れた場合、いかなる理由であっても無効とさせていただきます。<br>
                        ・⼊場チケットは、お客さまのスマートフォンで表⽰いただく形式になります。お並びになる前に、⼊場チケットの表⽰をお願いいたします。<br>
                        尚、画⾯のスクリーンショットでのご提⽰は無効とさせていただきます。<br>
                        ・お申し込み時の⽒名と証明書の⽒名の⼀致を確認の上ご⼊場となります。ご本⼈確認のための上記証明書をお持ちでない⽅は、ご⼊場いただけませんのでご了承ください。<br>
                        ・当⽇の混雑状況によっては、ご⼊場までに⻑時間お待ちいただく場合がございます。お時間には余裕をもってお越しください。<br>
                        ・ご⼊場の際、⼩学⽣以下のお⼦さまのご同伴は可能です。ただし当選されたご本⼈のみご購⼊いただけます。<br>
                        ・「同⼀デザインの商品は、サイズ違いも含めておひとり様２点までご購⼊いただけます。」<br>
                    </div>
                </div>
                <div class="contents-row">
                    <div class="content-title">お問い合わせ</div>
                    <div class="content-items">
                        イベントのお問い合わせは、下記までお願いします。<br>
                        2営業日〜担当からご連絡します。<br>
                        PARCOカードのお申し込みについてのお問い合わせ先ではございません。<br>
                        info@etro-shibuya-parco-cp.com
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
