<!DOCTYPE html>
<html>
<body>

下記内容の申し込みを受け付けました<br>
■名前<br>
　{{ $application->sei }} {{ $application->mei }} <br>
　{{ $application->sei_kana }} {{ $application->mei_kana }} <br>
■電話番号<br>
　{{ $application->tel }}<br>
■メールアドレス<br>
　{{ $application->email }}<br>
■性別<br>
　{{ \App\Consts\GinzaConst::SEX_LIST[$application->sex] }}<br>
■年齢<br>
　{{ $application->age }}歳<br>
<br>
応募内容は下記からご確認いただけます<br>
<a href="{{ route('admin_dashboard') }}">管理画面</a><br>
<br>
<br>
</body>
</html>
