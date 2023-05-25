<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   StandardDateFormat.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

use DateTimeInterface;

trait StandardDateFormat
{
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
