<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   CardDeck.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Helpers\Games;

use Exception;
use Illuminate\Contracts\Support\Arrayable;

class CardDeck implements Arrayable
{
    
    protected $deck;

    
    protected $dealtCards;

    public function __construct($deck = NULL)
    {
        if ($deck) {
            $this->set($deck);
        } else {
            $this->deck = new CardSet();

            CardSuit::all()->each(function ($suit) {
                CardValue::all()->each(function ($value) use ($suit) {
                    $this->deck->push($suit . $value);
                });
            });

            $this->shuffle();
        }

        $this->dealtCards = new CardSet();

        return $this;
    }

    
    public function get(): CardSet
    {
        return $this->deck;
    }

    
    public function set($deck): CardDeck
    {
        $this->deck = new CardSet($deck);

        return $this;
    }

    
    public function replace($cards): CardDeck
    {
        $this->deck = $this->deck->replace(new CardSet($cards));

        return $this;
    }

    
    public function shuffle(): CardDeck
    {
        $this->deck = $this->deck->shuffle(random_int(0, 999999));

        return $this;
    }

    
    public function cut(int $numberOfCards): CardDeck
    {
        
        $cards = $this->deck->splice($numberOfCards);

        $this->deck = $cards->concat($this->deck);

        return $this;
    }

    
    public function deal(): Card
    {
        $card = $this->deck->shift();
        $this->dealtCards->push($card);

        return $card;
    }

    
    public function dealSet(int $numberOfCards): CardSet
    {
        if ($numberOfCards < 1) {
            throw new Exception(sprintf('You should pass an integer greater than 1, %d passed.', $numberOfCards));
        }

        $cards = $this->deck->shift($numberOfCards);
        $this->dealtCards->push(...$cards);

        return $cards;
    }

    
    public function remove(int $numberOfCards): CardDeck
    {
        $this->deck->shift($numberOfCards);

        return $this;
    }

    
    public function getCard(int $n): Card
    {
        return $this->deck->get($n - 1);
    }

    
    public function getCards(int $count, int $n = 1): CardSet
    {
        return $this->deck->slice($n - 1, $count)->values();
    }

    public function toArray(): array
    {
        return $this->deck->toArray();
    }
}
