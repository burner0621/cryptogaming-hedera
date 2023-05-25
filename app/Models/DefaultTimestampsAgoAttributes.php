<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   DefaultTimestampsAgoAttributes.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

trait DefaultTimestampsAgoAttributes
{
    
    public function getCreatedAgoAttribute()
    {
        return $this->created_at ? $this->created_at->diffForHumans() : NULL;
    }

    
    public function getUpdatedAgoAttribute()
    {
        return $this->updated_at ? $this->updated_at->diffForHumans() : NULL;
    }
}
