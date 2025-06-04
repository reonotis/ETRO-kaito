<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        @vite(['resources/scss/application.scss'])

        <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script> <!-- 住所入力 -->
    </x-slot>

    <div class="p-2 sm:p-6">
        <div class="ticket-container">
            <div class="event-title">
                ETRO per Kaito Takahashi<br>
                渋⾕PARCO 1階ポップアップスペースGATE<br>
                6月27日（金）入場抽選応募フォーム
            </div>
            <div class="content-area">この画面をスタッフに見せてください</div>
            <div class="content-area">
                <div class="user-info">{{ $application->sei . ' ' . $application->mei }} 様</div>
                <div class="unique-code">管理番号： {{ $application->unique_code }}</div>
            </div>
            <div class="warning-text">一度スライドしたチケットは無効となりますので、<br>運営スタッフではない方は触らないようにして下さい</div>
            <div class="slider-container">
                <input type="range" id="slider" min="0" max="100" value="0" style="width: 80%;"/>
            </div>
            <form id="tearTicketForm" action="{{ route('tear_ticket', ['unique_code' => $application->unique_code]) }}"
                  style="display: none;"></form>
        </div>
    </div>
</x-application-layout>

<style>
</style>

<script>
    const slider = document.getElementById('slider');
    const form = document.getElementById('tearTicketForm');

    let isSliding = false;

    slider.addEventListener('input', () => {
        isSliding = true;
    });

    slider.addEventListener('change', () => {
        if (slider.value === "100") {
            form.submit();
        } else if (isSliding) {
            slider.value = 0;
            isSliding = false;
        }
    });
</script>
