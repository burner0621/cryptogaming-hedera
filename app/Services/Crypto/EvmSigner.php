<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   EvmSigner.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Services\Crypto;

use Elliptic\EC;
use Illuminate\Support\Str;
use kornrunner\Keccak;

class EvmSigner implements Signer
{
    protected const PREFIX = '0x';
    protected const PK_LENGTH = 64;
    protected const CURVE = 'secp256k1';

    public function getPrefix(): string
    {
        return static::PREFIX;
    }

    public function sha3(string $string): string
    {
        return Keccak::hash($string, 256);
    }

    
    public function hash(string $message): string
    {
        return $this->sha3(sprintf("\x19Ethereum Signed Message:\n%d%s", strlen($message), $message));
    }

    
    public function sign(string $privateKey, string $message): string
    {
        if (strlen($privateKey) != static::PK_LENGTH) {
            throw new \Exception(sprintf('Private key must be %d characters long (%d provided)', static::PK_LENGTH, strlen($privateKey)));
        }

        $hash = $this->hash($message);
        $ec = new EC(static::CURVE);
        $ecPrivateKey = $ec->keyFromPrivate($privateKey, 'hex');
        $signature = $ecPrivateKey->sign($hash, ['canonical' => TRUE, 'n' => NULL]);
        $r = str_pad($signature->r->toString(16), static::PK_LENGTH, '0', STR_PAD_LEFT);
        $s = str_pad($signature->s->toString(16), static::PK_LENGTH, '0', STR_PAD_LEFT);
        $v = dechex($signature->recoveryParam + 27);

        return Str::of($r . $s . $v)->start(static::PREFIX);
    }

    
    public function verify(string $message, string $signature, string $address): bool
    {
        $hash = $this->hash($message);
        $recId = ord(hex2bin(substr($signature, 130, 2))) - 27;

        if ($recId != ($recId & 1)) {
            return FALSE;
        }

        $ec = new EC(static::CURVE);
        $publicKey = $ec->recoverPubKey($hash, $this->formatSignature($signature), $recId);

        return $this->addressToHex($address) == $this->publicKeyToAddress($publicKey->encode('hex'));
    }

    
    public function privateKeyToPublicKey(string $privateKey): string
    {
        if (strlen($privateKey) != static::PK_LENGTH) {
            throw new \Exception(sprintf('Private key must be %d characters long (%d provided)', static::PK_LENGTH, strlen($privateKey)));
        }

        $ec = new EC(static::CURVE);
        $keyPair = $ec->keyFromPrivate($privateKey, 'hex');
        return $keyPair->getPublic(FALSE, 'hex');
    }

    
    public function publicKeyToAddress(string $publicKey): string
    {
        return Str::of($this->sha3(Str::of(hex2bin($publicKey))->substr(1)))->substr(24)->start(static::PREFIX);
    }

    
    public function privateKeyToAddress(string $privateKey): string
    {
        return $this->publicKeyToAddress($this->privateKeyToPublicKey($privateKey));
    }

    
    public function getMethodSignature(string $method): string
    {
        return Str::of($this->sha3($method))->substr(0, 8)->start(static::PREFIX);
    }

    protected function addressToHex(string $address): string
    {
        return Str::lower($address);
    }

    protected function formatSignature(string $signature): array
    {
        return [
            'r' => substr($signature, 2, static::PK_LENGTH),
            's' => substr($signature, static::PK_LENGTH + 2, static::PK_LENGTH)
        ];
    }
}
