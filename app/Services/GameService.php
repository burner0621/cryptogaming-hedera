<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   GameService.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Services;

use App\Events\GamePlayed;
use App\Facades\AccountTransaction;
use App\Helpers\PackageManager;
use App\Models\Account;
use App\Models\Game;
use App\Models\ProvablyFairGame;
use App\Models\User;
use App\Repositories\ProvablyFairGameRepository;

abstract class GameService
{
    private $user;
    private $provablyFairGame;

    protected $appends = []; 
    protected $makeVisible = []; 
    protected $gameableClass;
    protected $packageId;
    protected $gameable;

    public function __construct(User $user)
    {
        if (!$this->gameableClass) {
            throw new \Exception('Gameable model should be explicitly set in the child class before calling GameService constructor.');
        }

        
        $this->user = $user->getKey() ? $user : auth()->user();

        $packageManager = app()->make(PackageManager::class);
        $this->packageId = $packageManager->getPackageIdByClass($this->gameableClass);
    }

    public function createProvablyFairGame(string $clientSeed): GameService
    {
        $this->provablyFairGame = new ProvablyFairGame();
        $this->provablyFairGame->secret = $this->makeSecret();
        $this->provablyFairGame->client_seed = $clientSeed;
        $this->provablyFairGame->gameable_type = $this->gameableClass;
        $this->provablyFairGame->save();

        return $this;
    }

    public function loadProvablyFairGame(string $hash): GameService
    {
        $this->provablyFairGame = ProvablyFairGameRepository::search($hash, $this->gameableClass);
        $this->provablyFairGame->loadMissing(['game', 'game.gameable']);

        return $this;
    }

    
    protected function createNextGame(): Game
    {
        $childGameService = get_called_class();
        
        
        return (new $childGameService($this->user))->create();
    }

    public function getProvablyFairGame(): ProvablyFairGame
    {
        return $this->provablyFairGame;
    }

    public function config($param)
    {
        return config($this->packageId . '.' . $param);
    }

    
    public function getGame(): ?Game
    {
        return $this->getProvablyFairGame()->game;
    }

    public function getGameable()
    {
        return $this->getGame() ? $this->getGame()->gameable : $this->gameable;
    }

    public function unsetGameable(): GameService
    {
        unset($this->getGame()->gameable);

        return $this;
    }

    public function isGameCompleted(): bool
    {
        return $this->getGame()->is_completed ?? FALSE;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getUserAccount(): Account
    {
        return $this->getUser()->account;
    }

    public function getUserAccountBalance(): float
    {
        return $this->getUserAccount()->balance;
    }

    
    final protected function save(array $gameAttributes): GameService
    {
        
        $gameable = $this->getGameable();

        
        $gameable->save();

        
        $gameExists = boolval($this->getGame());
        if (!$gameExists) {
            $this->provablyFairGame->game = new Game();
            $this->provablyFairGame->game->account()->associate($this->getUserAccount());
            $this->provablyFairGame->game->provablyFairGame()->associate($this->provablyFairGame);
        }

        
        $currentBet = $this->provablyFairGame->game->bet ?? 0;
        $bet = $gameAttributes['bet'] ?? $currentBet;
        $win = $gameAttributes['win'] ?? 0;
        $transactionAmount = $win - $bet + $currentBet;

        
        foreach ($gameAttributes as $key => $value) {
            $this->provablyFairGame->game->{$key} = $value;
        }

        
        if (!$gameExists) {
            $gameable->game()->save($this->provablyFairGame->game);
        
        } else {
            $this->provablyFairGame->game->save();
        }

        
        $gameable->append($this->appends);
        
        $this->provablyFairGame->game->gameable = $gameable;

        
        AccountTransaction::create(
            $this->getUserAccount(),
            $this->getGame(),
            $transactionAmount,
        );

        
        if ($this->isGameCompleted()) {
            
            $this->provablyFairGame->game->gameable->makeVisible($this->makeVisible);

            
            $childGameService = get_called_class();
            $this->provablyFairGame->game->pf_game = (new $childGameService($this->getUser()))->createProvablyFairGame($this->getProvablyFairGame()->client_seed)->getProvablyFairGame();

            
            event(new GamePlayed($this->getGame()));
        }

        
        $this->provablyFairGame->game->makeHidden(['id']);
        $this->provablyFairGame->game->gameable->makeHidden(['id']);

        return $this;
    }

    
    abstract public function makeSecret(): string;
}
