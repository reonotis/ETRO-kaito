<x-application-layout>

    <x-slot name="title">整理券について</x-slot>

    <x-slot name="script">
        @vite(['resources/scss/application.scss'])
    </x-slot>

    <div class="py-16">
        <div class="form-area">

            <div class="event-title">
                <img src="{{ asset('image/LOGO.png') }}" alt="ETRO logo" class="event-logo">
            </div>


            <div class="event-title">
                整理券について
            </div>


            <p style="font-weight: bold; text-align: end;">2026年8月28日12:00現在</p>

            {{-- TODO: 店舗ごとの展開アイテムを記載 --}}
            <div class="store-card-grid">
                <div class="store-card" x-data="{ open: false }">
                    <button type="button" class="store-card-header" @click="open = !open"
                            :aria-expanded="open.toString()">
                        <span>エトロ銀座本店</span>
                        <span class="store-card-toggle-icon" :class="{ 'is-open': open }">▾</span>
                    </button>
                    <div class="store-card-body" x-show="open" x-cloak x-transition>
                        <div class="store-card-row">
                            <span class="store-card-label">配布方法</span>
                            <span class="store-card-value">混雑状況により配布</span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value">ショップ入口</span>
                        </div>
                    </div>
                </div>
                <div class="store-card" x-data="{ open: false }">
                    <button type="button" class="store-card-header" @click="open = !open"
                            :aria-expanded="open.toString()">
                        <span>日本橋三越本店（ウィメンズ）</span>
                        <span class="store-card-toggle-icon" :class="{ 'is-open': open }">▾</span>
                    </button>
                    <div class="store-card-body" x-show="open" x-cloak x-transition>
                        <div class="store-card-row">
                            <span class="store-card-label">配布方法</span>
                            <span class="store-card-value">開店前に整理券事前配布<br>開始時間：9月2日(水)午前9時半～</span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value">日本橋三越本店 新館地下1階 半蔵門線口</span>
                        </div>
                    </div>
                </div>
                <div class="store-card" x-data="{ open: false }">
                    <button type="button" class="store-card-header" @click="open = !open"
                            :aria-expanded="open.toString()">
                        <span>新宿髙島屋店</span>
                        <span class="store-card-toggle-icon" :class="{ 'is-open': open }">▾</span>
                    </button>
                    <div class="store-card-body" x-show="open" x-cloak x-transition>
                        <div class="store-card-row">
                            <span class="store-card-label">配布方法</span>
                            <span class="store-card-value">随時更新</span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value">随時更新</span>
                        </div>
                    </div>
                </div>
                <div class="store-card" x-data="{ open: false }">
                    <button type="button" class="store-card-header" @click="open = !open"
                            :aria-expanded="open.toString()">
                        <span>西武池袋本店</span>
                        <span class="store-card-toggle-icon" :class="{ 'is-open': open }">▾</span>
                    </button>
                    <div class="store-card-body" x-show="open" x-cloak x-transition>
                        <div class="store-card-row">
                            <span class="store-card-label">配布方法</span>
                            <span class="store-card-value">混雑状況により配布</span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value">ショップ入口</span>
                        </div>
                    </div>
                </div>
                <div class="store-card" x-data="{ open: false }">
                    <button type="button" class="store-card-header" @click="open = !open"
                            :aria-expanded="open.toString()">
                        <span>横浜髙島屋店</span>
                        <span class="store-card-toggle-icon" :class="{ 'is-open': open }">▾</span>
                    </button>
                    <div class="store-card-body" x-show="open" x-cloak x-transition>
                        <div class="store-card-row">
                            <span class="store-card-label">配布方法</span>
                            <span class="store-card-value">随時更新</span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value">随時更新</span>
                        </div>
                    </div>
                </div>
                <div class="store-card" x-data="{ open: false }">
                    <button type="button" class="store-card-header" @click="open = !open"
                            :aria-expanded="open.toString()">
                        <span>三越札幌店</span>
                        <span class="store-card-toggle-icon" :class="{ 'is-open': open }">▾</span>
                    </button>
                    <div class="store-card-body" x-show="open" x-cloak x-transition>
                        <div class="store-card-row">
                            <span class="store-card-label">配布方法</span>
                            <span class="store-card-value">混雑状況により配布</span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value">ショップ入口</span>
                        </div>
                    </div>
                </div>
                <div class="store-card" x-data="{ open: false }">
                    <button type="button" class="store-card-header" @click="open = !open"
                            :aria-expanded="open.toString()">
                        <span>仙台藤崎店</span>
                        <span class="store-card-toggle-icon" :class="{ 'is-open': open }">▾</span>
                    </button>
                    <div class="store-card-body" x-show="open" x-cloak x-transition>
                        <div class="store-card-row">
                            <span class="store-card-label">配布方法</span>
                            <span class="store-card-value">随時更新</span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value">随時更新</span>
                        </div>
                    </div>
                </div>
                <div class="store-card" x-data="{ open: false }">
                    <button type="button" class="store-card-header" @click="open = !open"
                            :aria-expanded="open.toString()">
                        <span>松坂屋名古屋店</span>
                        <span class="store-card-toggle-icon" :class="{ 'is-open': open }">▾</span>
                    </button>
                    <div class="store-card-body" x-show="open" x-cloak x-transition>
                        <div class="store-card-row">
                            <span class="store-card-label">配布方法</span>
                            <span class="store-card-value">開店前に整理券事前配布<br>開始時間：9月2日(水)午前9時半～</span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value">本館1階東南入口</span>
                        </div>
                    </div>
                </div>
                <div class="store-card" x-data="{ open: false }">
                    <button type="button" class="store-card-header" @click="open = !open"
                            :aria-expanded="open.toString()">
                        <span>大丸京都店</span>
                        <span class="store-card-toggle-icon" :class="{ 'is-open': open }">▾</span>
                    </button>
                    <div class="store-card-body" x-show="open" x-cloak x-transition>
                        <div class="store-card-row">
                            <span class="store-card-label">配布方法</span>
                            <span class="store-card-value">開店前に整理券事前配布<br>開始時間：9月2日(水)午前9時45分～</span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value">東洞院入口</span>
                        </div>
                    </div>
                </div>
                <div class="store-card" x-data="{ open: false }">
                    <button type="button" class="store-card-header" @click="open = !open"
                            :aria-expanded="open.toString()">
                        <span>大丸心斎橋店</span>
                        <span class="store-card-toggle-icon" :class="{ 'is-open': open }">▾</span>
                    </button>
                    <div class="store-card-body" x-show="open" x-cloak x-transition>
                        <div class="store-card-row">
                            <span class="store-card-label">配布方法</span>
                            <span class="store-card-value">随時更新</span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value">随時更新</span>
                        </div>
                    </div>
                </div>
                <div class="store-card" x-data="{ open: false }">
                    <button type="button" class="store-card-header" @click="open = !open"
                            :aria-expanded="open.toString()">
                        <span>大丸神戸店</span>
                        <span class="store-card-toggle-icon" :class="{ 'is-open': open }">▾</span>
                    </button>
                    <div class="store-card-body" x-show="open" x-cloak x-transition>
                        <div class="store-card-row">
                            <span class="store-card-label">配布方法</span>
                            <span class="store-card-value">混雑状況により配布</span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value">ショップ入口</span>
                        </div>
                    </div>
                </div>
                <div class="store-card" x-data="{ open: false }">
                    <button type="button" class="store-card-header" @click="open = !open"
                            :aria-expanded="open.toString()">
                        <span>広島福屋店</span>
                        <span class="store-card-toggle-icon" :class="{ 'is-open': open }">▾</span>
                    </button>
                    <div class="store-card-body" x-show="open" x-cloak x-transition>
                        <div class="store-card-row">
                            <span class="store-card-label">配布方法</span>
                            <span class="store-card-value">混雑状況により配布</span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value">ショップ入口</span>
                        </div>
                    </div>
                </div>
                <div class="store-card" x-data="{ open: false }">
                    <button type="button" class="store-card-header" @click="open = !open"
                            :aria-expanded="open.toString()">
                        <span>岩田屋本店</span>
                        <span class="store-card-toggle-icon" :class="{ 'is-open': open }">▾</span>
                    </button>
                    <div class="store-card-body" x-show="open" x-cloak x-transition>
                        <div class="store-card-row">
                            <span class="store-card-label">配布方法</span>
                            <span class="store-card-value">開店前に整理券事前配布配布<br>開始時間：9月2日(水)午前9時半～	</span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value">本館1階1F正面玄関前</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contents-row">
                <div class="content-title">お買い物に関する注意事項</div>
                <div class="content-items">
                    ・同⼀デザインの商品は、サイズ違いも含めてお⼀⼈様1点までのご購⼊とさせていただきます。（⾊違いはご購⼊可）<br>
                    ・お買い物はお一人様30分とさせて頂いております。時間内のお買い物にご協力ください。<br>
                    ・商品のお取り置き対応はいたしかねます。<br>
                    ・お客様都合による返品・交換はお受けいたしかねますので、ご確認のうえご購⼊ください。<br>
                    ・整理券はご購入をお約束するものではございません。完売の際はご容赦ください。<br>
                    ・整理券の発行枚数には限りがございます。<br>
                    ・販売店舗への事前の在庫確認等はお控えください。<br>
                </div>
            </div>

        </div>
    </div>
</x-application-layout>
