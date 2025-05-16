<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        @vite(['resources/scss/application.scss'])

        <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script> <!-- 住所入力 -->
    </x-slot>

    <div class="p-6">
        <div class="ticket-container">
            <div class="event-title">ETRO×高橋海人カプセルコレクション 渋谷パルコ当選チケット</div>
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
    .ticket-container {
        max-width: 600px;
        width: 100%;
        margin: 0 auto;
        padding: 20px 30px;
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .event-title {
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 10px;
    }
    .content-area { text-align: center; margin-bottom: 15px; color: #333; }
    .user-info { font-size: 24px; font-weight: bold; }
    .unique-code { font-family: monospace; font-size: 18px; color: #555; }
    .warning-text { color: red; font-weight: bold; text-align: center; margin-bottom: 20px; }
    .slider-container { display: flex; justify-content: center; align-items: center; }


    input[type='range'] {
        -webkit-appearance: none;
        width: 80%;
        height: 50px;
        background: #f1f1f1;
        border-radius: 10px;
        outline: none;
        transition: background 0.3s;
        border: 2px solid #0d0d0d;
    }

    input[type='range']::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 40px;
        height: 50px;
        background: #ccc;
        border-radius: 5px;
        box-shadow: inset 0 0 3px rgba(0,0,0,0.3);
        border: 2px solid #0d0d0d;
        transition: background 0.3s;
    }

    input[type='range']:hover::-webkit-slider-thumb {
        background: linear-gradient(135deg, #357ABD, #4A90E2);
    }

    input[type='range']:active::-webkit-slider-thumb {
        background: linear-gradient(135deg, #2A6496, #4A90E2);
    }
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
