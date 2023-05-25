<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   SolanaSigner.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Services\Crypto;

use Illuminate\Support\Facades\Log;
use SodiumException;
use StephenHill\Base58;

class SolanaSigner implements Signer
{
    protected $base58;

    public function __construct()
    {
        $this->base58 = new Base58();
    }

    public function sign(string $privateKey, string $message): string
    {
        return $this->base58->encode(sodium_crypto_sign_detached($message, $this->base58->decode($privateKey)));
    }

    public function verify(string $message, string $signature, string $address): bool
    {
        try {
            return sodium_crypto_sign_verify_detached($this->base58->decode($signature), $message, $this->base58->decode(($address)));
        } catch (SodiumException $e) {
            Log::error(sprintf('Error when verifying Solana signature: %s', $e->getMessage()));
            return FALSE;
        }
    }
}
