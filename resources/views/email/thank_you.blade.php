<!DOCTYPE html>
<html>
<body>

<img src="{{ route('mail_open', ['unique_code' => $application->unique_code]) }}?{{ date('YmdHis') }}" width="1" height="1" alt="Email Tracking Pixel"><br>

(Please scroll down for English)<br>
<br>
{{ $application->name }} 様<br>
<br>
この度は、Tokyo Watch Week への申込みありがとうございます。<br>
<br>
下記URLを現地の受付スタッフへご提示ください。<br>
<br>
<a href="{{ route('view_ticket', ['unique_code' => $application->unique_code]) }}">URL</a><br>
<br>
それでは当日お会いできるのを楽しみにしております。<br>
<br>
------------------------------------------------------------<br>
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
<br>
</body>
</html>
