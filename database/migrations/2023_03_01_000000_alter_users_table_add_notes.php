<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   2023_03_01_000000_alter_users_table_add_notes.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableAddNotes extends Migration
{
    
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('permissions');
        });
    }

    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
}
