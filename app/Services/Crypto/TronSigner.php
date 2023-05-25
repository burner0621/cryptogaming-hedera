<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   TronSigner.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Services\Crypto;

use IEXBase\TronAPI\Support\Base58Check;

class TronSigner extends EvmSigner
{
    protected const PREFIX = '41';

    
    public function hash(string $message): string
    {
        
        return $this->sha3(sprintf("\x19TRON Signed Message:\n32%s", $message));
    }

    protected function addressToHex(string $address): string
    {
        return Base58Check::decode($address, 0, 3);
    }

    protected function formatSignature(string $signature): array
    {
        return [
            'recoveryParam' => substr($signature, 130, 2) == '1c' ? 1 : 0,
            'r' => substr($signature, 2, static::PK_LENGTH),
            's' => substr($signature, static::PK_LENGTH + 2, static::PK_LENGTH)
        ];
    }
}
