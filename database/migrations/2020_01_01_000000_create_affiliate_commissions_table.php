<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   2020_01_01_000000_create_affiliate_commissions_table.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateCommissionsTable extends Migration
{
    
    public function up()
    {
        Schema::create('affiliate_commissions', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('referral_account_id');
            $table->unsignedTinyInteger('tier');
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('status');
            $table->decimal('amount', 20, 2);
            $table->morphs('commissionable', 'affiliate_commission_morph'); 
            $table->ipAddress('ip');
            $table->timestamps();
            
            $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('referral_account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('affiliate_commissions');
    }
}
