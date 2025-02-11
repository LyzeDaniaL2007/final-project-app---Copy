<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResetPasswordColumnsToAdminTable extends Migration
{
    public function up()
    {
        Schema::table('admin', function (Blueprint $table) {
            $table->string('reset_password_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('admin', function (Blueprint $table) {
            $table->dropColumn(['reset_password_token', 'token_expires_at']);
        });
    }
}
