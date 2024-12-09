<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id(); // ID列
            $table->string('name'); // グループ名
            $table->timestamps(); // 作成日時と更新日時
        });
    }

    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
