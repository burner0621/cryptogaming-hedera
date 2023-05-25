<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   Card.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/


namespace App\Helpers\Games;

use App\Helpers\MagicGetters;

class Card
{
    use MagicGetters;

    protected $suit;
    protected $value;

    public function __construct(string $code)
    {
        $this->suit = new CardSuit($code[0]);
        $this->value = new CardValue($code[1]);
    }

    protected function getCode(): string
    {
        return $this->getSuit() . $this->getValue();
    }

    protected function getSuit(): CardSuit
    {
        return $this->suit;
    }

    protected function getValue(): CardValue
    {
        return $this->value;
    }

    
    public function hasHigherValue(Card $card): bool
    {
        return $this->value->rank > $card->value->rank;
    }

    
    public function hasLowerValue(Card $card): bool
    {
        return $this->value->rank < $card->value->rank;
    }

    public function __toString()
    {
        return $this->getCode();
    }
}
