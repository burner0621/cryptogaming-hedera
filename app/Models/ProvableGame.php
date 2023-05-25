<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   ProvableGame.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/


namespace App\Models;

interface ProvableGame
{
    
    public function getSecretAttributeAttribute(): string;

    
    public function getSecretAttributeHintAttribute(): string;
}
