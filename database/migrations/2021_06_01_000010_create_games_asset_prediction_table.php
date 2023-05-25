<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   2021_06_01_000010_create_games_asset_prediction_table.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesAssetPredictionTable extends Migration
{
    
    public function up()
    {
        Schema::create('games_asset_prediction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('direction'); 
            $table->decimal('open_price', 18, 8);
            $table->decimal('close_price', 18, 8)->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();
            
            $table->index('created_at');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('games_asset_prediction');
    }
}
