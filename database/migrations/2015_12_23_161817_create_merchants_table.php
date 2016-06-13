<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_name')->unique()->nullable()->comment('luu sdt doi voi nhung thang dung sdt '); 
            $table->string('email')->unique()->nullable()->comment('duy nhat nhung duoc phep de trong');
            $table->string('full_name')->nullable()->comment('Ten merchan luu khi dang ky');
            $table->string('password');
            $table->string('password_confirmation')->nullable();
            $table->string('active_token')->nullable()->comment('luu doan ma sinh ra de kich hoat, kich hoat xong dat la null'); 
            $table->integer('active')->default(-1)->comment('-1: chua active email, 0: unactive, 1: active, 2 cho admin duyet');  
            $table->datetime('active_at')->nullable()->comment('luu ngay kich hoat'); 
            $table->string('facebook_token')->nullable();
            $table->string('google_token')->nullable();
            $table->rememberToken();

            $table->string('name')->nullable()->comment('ten thuong hieu'); 
            $table->string('logo')->nullable();
            $table->string('color')->default('#f94876')->comment('ma mau cua merchant'); 
            $table->string('text_color')->default('#ffffff')->comment('ma mau text cua merchant'); 
            $table->integer('field')->nullable()->comment('linh vuc hoat dong, lay trong bang optionvalues');
            $table->text('information')->nullable()->comment('luu thong tin chu merchant'); 
            
            $table->integer('card_type')->default(0)->comment('luu id loai card, lay trong bang options'); 
            $table->text('card_info')->nullable()->comment('luu json, loai card su dung'); 

            $table->integer('package')->default(0)->comment('lưu id của các gói dang su dung, lay trong bang optionvalues');  
            $table->datetime('start_day')->nullable()->comment('luu ngay bat dau co hieu luc, neu la free -> null'); 
            $table->datetime('end_day')->nullable()->comment('luu ngay het han goi, neu la free -> null'); 
            $table->integer('message_count')->default(1)->comment('so tin nhan duoc phep gui trong mot thang'); 
            // $table->integer('deal_count')->default(1)->comment('so uu dai duoc phep gui trong mot thang'); 
            $table->integer('package_status')->default(0)->comment('1 la pedding, 0 la not pedding'); 
            $table->string('slug')->unique()->nullable()->comment('luu url cua merchant');
            
            $table->integer('kind')->default(1)->comment('1 là thằng mới hoàn toàn = chop, 2 là thằng mới hoàn toàn = level, 3 là verifire = số điẹn thoại, 4 là thằng đồng bộ bằng email+password, 5 là mình tạo cho họ (co san/khong co barcode)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('merchants');
    }
}
