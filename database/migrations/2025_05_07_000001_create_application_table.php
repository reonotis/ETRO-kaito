<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('application', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');

            $table->string('unique_code', 15)->unique()->nullable();
            $table->string('sei')->comment('苗字');
            $table->string('mei')->comment('名前');
            $table->string('sei_kana')->comment('ミョウジ');
            $table->string('mei_kana')->comment('ナマエ');
            $table->integer('sex')->nullable()->comment('性別');
            $table->date('birthday')->nullable()->comment('生年月日');
            $table->integer('age')->nullable()->comment('年齢');
            $table->string('tel')->nullable()->comment('電話番号');
            $table->string('email')->nullable()->comment('メールアドレス');
            $table->string('zip21', '3')->nullable()->comment('郵便番号1');
            $table->string('zip22', '4')->nullable()->comment('郵便番号2');
            $table->string('pref21')->nullable()->comment('都道府県');
            $table->string('address21')->nullable()->comment('市区町村');
            $table->string('street21')->nullable()->comment('所在');
            $table->string('img_pass')->nullable()->comment('画像パス');
            $table->text('comment')->nullable()->comment('コメント');
            $table->text('comment2')->nullable()->comment('コメント2');
            $table->text('comment3')->nullable()->comment('コメント3');
            $table->text('choice_1')->nullable()->comment('選択項目1');
            $table->text('choice_2')->nullable()->comment('選択項目2');
            $table->text('choice_3')->nullable()->comment('選択項目3');
            $table->text('choice_4')->nullable()->comment('選択項目4');

            $table->dateTime('visit_scheduled_date_time')->nullable()->comment('来場予定日時');
            $table->boolean('sent_lottery_result_email_flg')->default(0)->comment('当選メール送信済みフラグ');
            $table->timestamp('email_opened_at')->nullable()->comment('当選メール開封日時');
            $table->dateTime('visit_date_time')->nullable()->comment('来場した日時');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application');
    }
};
