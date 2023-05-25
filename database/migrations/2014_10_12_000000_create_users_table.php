<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   2014_10_12_000000_create_users_table.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->unsignedBigInteger('referrer_id')->nullable();
            $table->string('name', 100)->unique();
            $table->string('email')->unique();
            $table->unsignedTinyInteger('role');
            $table->unsignedTinyInteger('status');
            $table->boolean('hide_profit')->default(FALSE);
            $table->boolean('banned_from_chat')->default(FALSE);
            $table->string('avatar', 100)->nullable();
            $table->text('permissions')->nullable(); 
            $table->string('password');
            $table->rememberToken();
            $table->string('totp_secret', 300)->nullable();
            $table->ipAddress('last_login_from')->nullable();
            $table->dateTime('last_login_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            
            $table->index('role');
            $table->index('status');
            $table->index('last_seen_at');
            
            $table->foreign('referrer_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
