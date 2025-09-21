<x-application-layout>

    <x-slot name="title">申込フォーム</x-slot>

    <x-slot name="script">
        <link rel="stylesheet" href="{{ asset('application.css') }}">
    </x-slot>

    <div class="logo-container">
        <img src="{{ asset('image/TWW_logo.png') }}" alt="TWW ロゴ">
    </div>

    <div class="p-2 sm:p-6">
        <div class="ticket-container">
            <div class="content-area">
                この画面をスタッフに見せてください<br>
                Please show this screen to the reception staff.
            </div>
            <div class="content-area">
                <div class="user-info">{{ $application->name }} 様</div>
                <div class="unique-code">管理番号： {{ $application->unique_code }}</div>
            </div>
            <div class="warning-text">
                運営スタッフではない方は触らないようにして下さい<br>
                Staff Only: Please do not swipe the below
            </div>
            <div class="slider-container">
                <input type="range" id="slider" min="0" max="100" value="0" style="width: 80%;"/>
            </div>
            <form id="tearTicketForm" action="{{ route('tear_ticket', ['unique_code' => $application->unique_code]) }}"
                method="post"
                  style="display: none;">
                @csrf
                </form>
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
