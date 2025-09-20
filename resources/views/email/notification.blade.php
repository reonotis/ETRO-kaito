<!DOCTYPE html>
<html>
<body>

下記内容の申し込みを受け付けました<br>
■名前<br>
　 {{ $application->name }}<br>
■電話番号<br>
　{{ $application->tel }}<br>
■メールアドレス<br>
　{{ $application->email }}<br>
■希望日<br>
@foreach($target_events as $target_event)
　{{ App\Consts\Common::TARGET_DATE_LIST[$target_event->target_number] }}<br>
@endforeach
<br>
応募内容は下記からご確認いただけます<br>
<a href="{{ route('dashboard') }}">管理画面</a><br>
<br>
<br>
</body>
</html>
