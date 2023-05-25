<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   2020_01_20_000010_create_chat_messages_table.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->bigInteger('room_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->text('message');
            $table->timestamps();
            
            $table->foreign('room_id')->references('id')->on('chat_rooms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
}
