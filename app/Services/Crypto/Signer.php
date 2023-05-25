<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   Signer.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Services\Crypto;

interface Signer
{
    public function sign(string $privateKey, string $message): string;

    public function verify(string $message, string $signature, string $address): bool;
}
