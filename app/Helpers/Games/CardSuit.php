<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   CardSuit.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/


namespace App\Helpers\Games;

use App\Helpers\MagicGetters;
use Illuminate\Support\Collection;

class CardSuit
{
    use MagicGetters;

    
    protected const SUITS = [
        'C', 'D', 'H', 'S'
    ];

    protected $code;

    public function __construct(string $code)
    {
        if (!in_array($code, self::SUITS)) {
            throw new \Exception(sprintf('Suit "%s" is not supported.', $code));
        }

        $this->code = $code;

        return $this;
    }

    protected function getRank(): int
    {
        return array_search($this->code, self::SUITS, TRUE);
    }

    protected function getCode(): string
    {
        return $this->code;
    }

    protected function getName(): string
    {
        if ($this->code == 'C') {
            return __('Clubs');
        } elseif ($this->code == 'D') {
            return __('Diamonds');
        } elseif ($this->code == 'H') {
            return __('Hearts');
        } else {
            return __('Spades');
        }
    }

    public function __toString()
    {
        return $this->getCode();
    }

    public static function all(): Collection
    {
        return collect(self::SUITS);
    }
}
