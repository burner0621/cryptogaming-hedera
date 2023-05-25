<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   2020_04_20_000001_create_game_rooms_table.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameRoomsTable extends Migration
{
    
    public function up()
    {
        Schema::create('game_rooms', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('user_id');
            $table->string('name', 50);
            $table->string('gameable_type');
            $table->unsignedTinyInteger('status');
            $table->text('parameters');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            
            $table->index(['gameable_type', 'status']);
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('game_rooms');
    }
}
