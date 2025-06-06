<!DOCTYPE html>
<html>
<body>

{{ $application->sei . ' ' . $application->mei }} 様<br>
<img src="{{ route('winner_mail_open', ['unique_code' => $application->unique_code]) }}?{{ date('YmdHis') }}" width="1" height="1" alt="Email Tracking Pixel"><br>
先日はETRO×高橋海人カプセルコレクション渋谷パルコへの申し込みをいただき誠にありがとうございます。<br>
抽選の結果、{{ $application->sei . $application->mei }}様をご招待する事が決定いたしました。<br>
つきましては {{ $application->visit_scheduled_date_time->isoFormat('MM月DD日(ddd) HH:mm') }} のセクションにご招待いたします。<br>
<br>
■日時<br>
・{{ $application->visit_scheduled_date_time->isoFormat('MM月DD日(ddd) HH:mm') }} <br>
<br>
■予定時間<br>
・30分<br>
<br>
■場所<br>
・ 渋谷パルコ 〒150-8377 東京都渋谷区宇田川町１５−１ 渋谷パルコ・ヒューリックビル B1-10階<br>
<br>
■入場方法<br>
・5分前までに会場前へお越しいただき、下記URLを係りの者にご提示ください<br>
<a href="{{ route('view_ticket', ['unique_code' => $application->unique_code]) }}">URL</a><br>
<br>
■注意事項<br>
・運転免許証など、写真付きの身分証をご持参ください<br>
・応募者名と一致しない場合はご入場いただけません<br>
・小学生以下1名まで同伴可<br>
・ご購入はお一人さま1アイテム2点まで。<br>
<br>
なお、ご都合がつかずに辞退される場合については、下記メールアドレスまでご連絡いただけますと幸いです。<br>
info@etro-shibuya-parco-cp.com<br>
※このメールは自動送信されているため、返信しても確認が取れません<br>
<br>
それでは当日のご来場をお待ちしております<br>
<br>
<br>
</body>
</html>
