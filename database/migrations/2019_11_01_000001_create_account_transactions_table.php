<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   2019_11_01_000001_create_account_transactions_table.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTransactionsTable extends Migration
{
    
    public function up()
    {
        Schema::create('account_transactions', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_id');
            $table->decimal('amount', 20, 2);
            $table->decimal('balance', 20, 2);
            $table->morphs('transactionable', 'account_transactions_morph'); 
            $table->timestamps();
            
            $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('account_transactions');
    }
}
