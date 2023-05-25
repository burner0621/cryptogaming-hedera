<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   CardSet.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class CardSet implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return new \App\Helpers\Games\CardSet(json_decode($value));
    }

    public function set($model, $key, $value, $attributes)
    {
        return json_encode(is_array($value) ? $value : $value->toArray());
    }
}
