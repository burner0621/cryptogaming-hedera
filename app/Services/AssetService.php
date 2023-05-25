<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   AssetService.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Services;

class AssetService
{
    protected $api;

    public function __construct(AssetApi $api)
    {
        $this->api = $api;
    }
}
