<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   2019_11_01_000002_create_generic_account_transactions_table.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenericAccountTransactionsTable extends Migration
{
    
    public function up()
    {
        Schema::create('generic_account_transactions', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedMediumInteger('type');
            $table->decimal('amount', 20, 2);
            $table->timestamps();
            
            $table->index('type');
            
            $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('generic_account_transactions');
    }
}
