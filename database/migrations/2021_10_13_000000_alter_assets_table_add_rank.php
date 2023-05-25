<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   2021_10_13_000000_alter_assets_table_add_rank.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAssetsTableAddRank extends Migration
{
    
    public function up()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->unsignedInteger('rank')->after('name');
            $table->index('rank');
        });
    }

    
    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('rank');
        });
    }
}
