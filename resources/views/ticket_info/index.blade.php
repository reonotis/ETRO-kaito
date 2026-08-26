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
                        <div class="store-card-row">
                            <span class="store-card-label">展開アイテム</span>
                            <span class="store-card-value"></span>
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
                            <span class="store-card-value">開店前に館入口にて整理券を配布<br>整理券配布開始時間：9月2日（水）午前9時半〜</span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value">地下入口前</span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">展開アイテム</span>
                            <span class="store-card-value"></span>
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
                            <span class="store-card-value"></span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value"></span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">展開アイテム</span>
                            <span class="store-card-value"></span>
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
                        <div class="store-card-row">
                            <span class="store-card-label">展開アイテム</span>
                            <span class="store-card-value"></span>
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
                            <span class="store-card-value"></span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value"></span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">展開アイテム</span>
                            <span class="store-card-value"></span>
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
                        <div class="store-card-row">
                            <span class="store-card-label">展開アイテム</span>
                            <span class="store-card-value"></span>
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
                            <span class="store-card-value"></span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value"></span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">展開アイテム</span>
                            <span class="store-card-value"></span>
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
                            <span class="store-card-value"></span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value"></span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">展開アイテム</span>
                            <span class="store-card-value"></span>
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
                            <span class="store-card-value"></span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value"></span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">展開アイテム</span>
                            <span class="store-card-value"></span>
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
                            <span class="store-card-value"></span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value"></span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">展開アイテム</span>
                            <span class="store-card-value"></span>
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
                        <div class="store-card-row">
                            <span class="store-card-label">展開アイテム</span>
                            <span class="store-card-value"></span>
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
                        <div class="store-card-row">
                            <span class="store-card-label">展開アイテム</span>
                            <span class="store-card-value"></span>
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
                            <span class="store-card-value"></span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">場所</span>
                            <span class="store-card-value"></span>
                        </div>
                        <div class="store-card-row">
                            <span class="store-card-label">展開アイテム</span>
                            <span class="store-card-value"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contents-row">
                <div class="content-title">お買い物に関する注意事項</div>
                <div class="content-items">
                    @include('ginza.admission-notes')
                </div>
            </div>


        </div>
    </div>
</x-application-layout>
