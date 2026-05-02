<!DOCTYPE html>
<html>
<body>

下記内容の申し込みを受け付けました<br>
■店舗<br>
　{{ \App\Consts\StoreAllConst::STORE_LIST[$application->choice_1] }}<br>
■名前<br>
　{{ $application->sei }} {{ $application->mei }} <br>
　{{ $application->sei_kana }} {{ $application->mei_kana }} <br>
■性別<br>
　{{ \App\Consts\GinzaConst::SEX_LIST[$application->sex] }}<br>
■電話番号<br>
　{{ $application->tel }}<br>
■住所<br>
　{{ $application->zip21 }}-{{ $application->zip22 }}<br>
　{{ $application->pref21 }} {{ $application->address21 }} {{ $application->street21 }}<br>
■年齢<br>
　{{ $application->age }}歳<br>
■メールアドレス<br>
　{{ $application->email }}<br>
<br>
応募内容は下記からご確認いただけます<br>
<a href="{{ route('dashboard') }}">管理画面</a><br>
<br>
<br>
</body>
</html>
