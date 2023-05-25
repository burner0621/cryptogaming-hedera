<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   PokerHand.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/


namespace App\Helpers\Games;

use Exception;
use Illuminate\Support\Collection;

class PokerHand
{
    public const MIN_CARDS = 2;
    public const MAX_CARDS = 7;

    public const HAND_HIGH_CARD = 0;
    public const HAND_ONE_PAIR = 1;
    public const HAND_TWO_PAIR = 2;
    public const HAND_THREE_OF_A_KIND = 3;
    public const HAND_STRAIGHT = 4;
    public const HAND_FLUSH = 5;
    public const HAND_FULL_HOUSE = 6;
    public const HAND_FOUR_OF_A_KIND = 7;
    public const HAND_STRAIGHT_FLUSH = 8;
    public const HAND_ROYAL_FLUSH = 9;

    public const RESULT_WIN = 1;
    public const RESULT_PUSH = 0;
    public const RESULT_LOSE = -1;

    protected $cards;
    protected $cardsByRankAcesHigh;
    protected $cardsByRankAcesLow;
    protected $hand;
    protected $kickers;
    protected $rank = -1;

    public function __construct(CardSet $pocketCards, CardSet $communityCards = NULL)
    {
        if (is_null($communityCards)) {
            $communityCards = new CardSet();
        }

        
        $this->cards = $pocketCards->concat($communityCards);

        if ($this->getCards()->count() < self::MIN_CARDS || $this->getCards()->count() > self::MAX_CARDS) {
            throw new Exception(sprintf('PokerHand should consist of %d - %d cards, %d passed.', self::MIN_CARDS, self::MAX_CARDS, $this->getCards()->count()));
        }

        $this->hand = new CardSet();
        $this->kickers = new CardSet();

        $this->make();

        return $this;
    }

    public function get(): CardSet
    {
        return $this->hand;
    }

    public function getCards(): CardSet
    {
        return $this->cards;
    }

    public function getKickers(): CardSet
    {
        return $this->kickers;
    }

    public function getRank(): int
    {
        return $this->rank;
    }

    
    public function compare(PokerHand $opponentHand): int
    {
        $rank = $this->getRank();

        
        if ($rank > $opponentHand->getRank()) {
            return self::RESULT_WIN;
        
        } elseif ($rank == $opponentHand->getRank()) {
            
            if ($rank == self::HAND_ROYAL_FLUSH) {
                return self::RESULT_PUSH;
            } elseif (in_array($rank, [self::HAND_STRAIGHT_FLUSH, self::HAND_FLUSH, self::HAND_STRAIGHT])) {
                
                return $this->compareCardCollections($this->get(), $opponentHand->get());
            } elseif ($rank == self::HAND_FOUR_OF_A_KIND) {
                
                $foursResult = $this->compareCards($this->get()->first(), $opponentHand->get()->first());

                
                return $foursResult != self::RESULT_PUSH ? $foursResult : $this->compareCards($this->get()->last(), $opponentHand->get()->last());
            } elseif ($rank == self::HAND_FULL_HOUSE) {
                
                $threesResult = $this->compareCards($this->get()->first(), $opponentHand->get()->first());

                
                return $threesResult != self::RESULT_PUSH ? $threesResult : $this->compareCards($this->get()->last(), $opponentHand->get()->last());
            } elseif ($rank == self::HAND_THREE_OF_A_KIND) {
                
                $threesResult = $this->compareCards($this->get()->first(), $opponentHand->get()->first());

                
                return $threesResult != self::RESULT_PUSH ? $threesResult : $this->compareCardCollections($this->getKickers(), $opponentHand->getKickers());
            } elseif ($rank == self::HAND_TWO_PAIR) {
                
                $highestPairResult = $this->compareCards($this->get()->first(), $opponentHand->get()->first());

                if ($highestPairResult != self::RESULT_PUSH) {
                    return $highestPairResult;
                }

                
                $lowestPairResult = $this->compareCards($this->get()->get(2), $opponentHand->get()->get(2));

                
                return $lowestPairResult != self::RESULT_PUSH ? $lowestPairResult : $this->compareCards($this->get()->last(), $opponentHand->get()->last());
            } elseif ($rank == self::HAND_ONE_PAIR) {
                
                $pairResult = $this->compareCards($this->get()->first(), $opponentHand->get()->first());

                
                return $pairResult != self::RESULT_PUSH ? $pairResult : $this->compareCardCollections($this->getKickers(), $opponentHand->getKickers());
            } elseif ($rank == self::HAND_HIGH_CARD) {
                
                $result = $this->compareCards($this->get()->first(), $opponentHand->get()->first());

                
                return $result != self::RESULT_PUSH ? $result : $this->compareCardCollections($this->getKickers(), $opponentHand->getKickers());
            }
        }

        return self::RESULT_LOSE;
    }

    
    protected function compareCards(Card $card, Card $card2): int
    {
        if ($card->hasHigherValue($card2)) {
            return self::RESULT_WIN;
        } elseif ($card->hasLowerValue($card2)) {
            return self::RESULT_LOSE;
        } else {
            return self::RESULT_PUSH;
        }
    }

    
    protected function compareCardCollections(CardSet $set, CardSet $set2): int
    {
        if ($set->count() != $set2->count()) {
            throw new Exception('Only collections of the same size can be compared.');
        }

        
        foreach ($set as $i => $card) {
            $result = $this->compareCards($card, $set2->get($i));
            
            if ($result != self::RESULT_PUSH) {
                return $result;
            }
        }

        return self::RESULT_PUSH;
    }

    public function getHighCard(): CardSet
    {
        return new CardSet([$this->getCardsSortedByRank()->first()]);
    }

    public function getOnePair(): ?CardSet
    {
        $hands = $this->getCardsOfSameRank(2);

        return $hands->isNotEmpty() ? $hands->first() : NULL;
    }

    public function getTwoPair(): ?CardSet
    {
        $hands = $this->getCardsOfSameRank(2);

        return $hands->count() >= 2 ? $hands->first()->concat($hands->get(1)) : NULL;
    }

    public function getThreeOfAKind(): ?CardSet
    {
        $threes = $this->getCardsOfSameRank(3);

        return $threes->isNotEmpty() ? $threes->first() : NULL;
    }

    public function getStraight($flush = FALSE): ?CardSet
    {
        $sequences = collect();
        $lastSuit = NULL;
        $lastRank = 0;

        $unique = function($card) {
            return $card->value->code;
        };

        
        $cardSets = collect([$this->getCardsSortedByRank()->unique($unique), $this->getCardsSortedByRank(TRUE)->unique($unique)]);

        
        foreach ($cardSets as $cards) {
            foreach ($cards as $i => $card) {
                if ($i > 0
                    && (!$flush || $card->suit->code == $lastSuit)
                    && ($card->value->rank == $lastRank - 1
                        
                        || $card->value->is_ace && $sequences->last()->last()->value->is_two)) {
                    $sequences->last()->push($card);
                } else {
                    $sequences->push(new CardSet([$card]));
                }

                $lastSuit = $card->suit->code;
                $lastRank = $card->value->rank;
            }
        }

        $sequences = $sequences
            ->filter(function (CardSet $sequence) {
                return $sequence->count() >= 5;
            });

        return $sequences->isNotEmpty()
            ? $sequences
                ->sortBy(function (CardSet $sequence) {
                    return $sequence->count();
                })
                ->last()
                ->take(5)
                ->values()
            : NULL;
    }

    public function getFlush(): ?CardSet
    {
        
        $suit = $this->getCardSuitesWithCount()
            ->filter(function ($count) {
                return $count >= 5;
            })
            ->keys()
            ->first();

        if (!$suit) {
            return NULL;
        }

        $hand = $this->getCardsSortedByRank()
            ->filter(function ($card) use ($suit) {
                return $card->suit->code == $suit;
            })
            ->take(5)
            ->values();

        return $hand->count() == 5 ? $hand : NULL;
    }

    public function getFullHouse(): ?CardSet
    {
        $threes = $this->getCardsOfSameRank(3);
        $twos = $this->getCardsOfSameRank(2);

        return $threes->count() == 2
            ? $threes->first()->concat($threes->last()->take(2))
            : ($threes->isNotEmpty() && $twos->isNotEmpty()
                ? $threes->first()->concat($twos->first())
                : NULL);
    }

    public function getFourOfAKind(): ?CardSet
    {
        $hands = $this->getCardsOfSameRank(4);

        return $hands->count() == 1 ? $hands->first() : NULL;
    }

    public function getStraightFlush(): ?CardSet
    {
        return $this->getStraight(TRUE);
    }

    public function getRoyalFlush(): ?CardSet
    {
        $flush = $this->getFlush();

        return $flush && $flush->toCollection()->map->value->map->code->diff(['T','J','Q','K','A'])->isEmpty() ? $flush : NULL;
    }

    
    public function getCardsSortedByRank($acesLow = FALSE): CardSet
    {
        return $acesLow ? $this->getCardsSortedByRankAcesLow() : $this->getCardsSortedByRankAcesHigh();
    }

    
    protected function make(): PokerHand
    {
        if ($hand = $this->getRoyalFlush()) {
            $this->rank = self::HAND_ROYAL_FLUSH;
        } elseif ($hand = $this->getStraightFlush()) {
            $this->rank = self::HAND_STRAIGHT_FLUSH;
        } elseif ($hand = $this->getFourOfAKind()) {
            $this->rank = self::HAND_FOUR_OF_A_KIND;
        } elseif ($hand = $this->getFullHouse()) {
            $this->rank = self::HAND_FULL_HOUSE;
        } elseif ($hand = $this->getFlush()) {
            $this->rank = self::HAND_FLUSH;
        } elseif ($hand = $this->getStraight()) {
            $this->rank = self::HAND_STRAIGHT;
        } elseif ($hand = $this->getThreeOfAKind()) {
            $this->rank = self::HAND_THREE_OF_A_KIND;
        } elseif ($hand = $this->getTwoPair()) {
            $this->rank = self::HAND_TWO_PAIR;
        } elseif ($hand = $this->getOnePair()) {
            $this->rank = self::HAND_ONE_PAIR;
        } elseif ($hand = $this->getHighCard()) {
            $this->rank = self::HAND_HIGH_CARD;
        }

        
        if ($hand->count() < 5) {
            $this->kickers = $this->getCardsSortedByRank()
                
                ->filter(function ($card) use ($hand) {
                    return !$hand->contains($card->code);
                })
                ->take(5 - $hand->count())
                ->values();

            $hand = $hand->concat($this->kickers);
        }

        $this->hand = $hand;

        return $this;
    }

    
    protected function getCardsSortedByRankAcesLow(): CardSet
    {
        if ($this->cardsByRankAcesLow) {
            return $this->cardsByRankAcesLow;
        }

        $this->cardsByRankAcesLow = $this->getCards()
            ->sortBy(function ($card) {
                return $card->value->is_ace
                    ? -1 + $card->suit->rank / 10
                    : $card->value->rank + $card->suit->rank / 10;
            })
            ->reverse()
            ->values();

        return $this->cardsByRankAcesLow;
    }

    
    protected function getCardsSortedByRankAcesHigh(): CardSet
    {
        if ($this->cardsByRankAcesHigh) {
            return $this->cardsByRankAcesHigh;
        }

        $this->cardsByRankAcesHigh = $this->getCards()
            ->sortBy(function ($card) {
                return $card->value->rank + $card->suit->rank / 10;
            })
            ->reverse()
            ->values();

        return $this->cardsByRankAcesHigh;
    }

    
    protected function getCardSuitesWithCount(): Collection
    {
        return $this->getCardsSortedByRank()
            ->toCollection()
            ->mapToGroups(function($card) {
                return [$card->suit->code => 1];
            })
            ->map
            ->sum()
            ->sort();
    }

    
    protected function getCardValuesWithCount(): Collection
    {
        
        return $this->getCardsSortedByRank()
            ->toCollection()
            ->mapToGroups(function($card) {
                return [$card->value->code => 1];
            })
            ->map
            ->sum()
            ->sort();
    }

    
    protected function getCardsOfSameRank(int $count): Collection
    {
        $values = $this->getCardValuesWithCount()
            ->filter(function($valueCount) use ($count) {
                return $valueCount == $count;
            });

        if ($values->isEmpty()) {
            return collect();
        }

        return $values
            ->map(function ($count, $value) {
                return $this->getCardsSortedByRank()
                    ->filter(function ($card) use ($value) {
                        return $card->value->code == $value;
                    })
                    ->values();
            })
            ->sortByDesc(function (CardSet $cards) {
                return $cards->first()->value->rank;
            })
            ->values();
    }

    public function getCombination(): string
    {
        return self::getCombinationTitle($this->rank);
    }

    public static function getCombinationTitle(int $hand): string
    {
        if ($hand == self::HAND_HIGH_CARD) {
            return __('High card');
        } elseif ($hand == self::HAND_ONE_PAIR) {
            return __('Pair');
        } elseif ($hand == self::HAND_TWO_PAIR) {
            return __('Two pair');
        } elseif ($hand == self::HAND_THREE_OF_A_KIND) {
            return __('Three of a kind');
        } elseif ($hand == self::HAND_STRAIGHT) {
            return __('Straight');
        } elseif ($hand == self::HAND_FLUSH) {
            return __('Flush');
        } elseif ($hand == self::HAND_FULL_HOUSE) {
            return __('Full house');
        } elseif ($hand == self::HAND_FOUR_OF_A_KIND) {
            return __('Four of a kind');
        } elseif ($hand == self::HAND_STRAIGHT_FLUSH) {
            return __('Straight flush');
        } elseif ($hand == self::HAND_ROYAL_FLUSH) {
            return __('Royal flush');
        } else {
            return __('Unknown hand');
        }
    }
}
