<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   2019_11_02_000001_create_games_table.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('provably_fair_game_id');
            $table->morphs('gameable', 'game_morph'); 
            $table->decimal('bet', 16, 2);
            $table->decimal('win', 16, 2);
            $table->tinyInteger('status');
            $table->timestamps();
            
            $table->index('status');
            $table->index('win');
            $table->index('created_at');
            
            $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('provably_fair_game_id')->references('id')->on('provably_fair_games')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
