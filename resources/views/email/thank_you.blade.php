<!DOCTYPE html>
<html>
<body>

<img src="{{ route('mail_open', ['unique_code' => $application->unique_code]) }}?{{ date('YmdHis') }}" width="1" height="1" alt="Email Tracking Pixel"><br>

<br>
<br>
<br>
<br>
(Please scroll down for English)<br>
<br>
Dear {{ $application->name }} ,<br>
<br>
We appreciate your registration for Tokyo Watch Week 2025.<br>
<br>
Please show the below URL to the reception staff on the day of the event.<br>
<br>
<a href="{{ route('view_ticket', ['unique_code' => $application->unique_code]) }}">URL</a><br>
<br>
We look forward to seeing you soon.<br>
<br>
(登録メール・チケット画面）<br>
<br>
</body>
</html>
