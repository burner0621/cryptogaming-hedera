<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   ChatMessage.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ChatMessage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'chat_message';
    }
}
