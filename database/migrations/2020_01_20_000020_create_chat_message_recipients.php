<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   2020_01_20_000020_create_chat_message_recipients.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessageRecipients extends Migration
{
    
    public function up()
    {
        Schema::create('chat_message_recipients', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->bigInteger('message_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->timestamps();
            
            $table->foreign('message_id')->references('id')->on('chat_messages')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unique(['message_id', 'user_id']);
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('chat_message_recipients');
    }
}
