<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   MultiroomMultiplayerGameService.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Services;

use App\Events\GamePlayed;
use App\Events\MultiroomMultiplayerGameAction;
use App\Facades\AccountTransaction;
use App\Models\Account;
use App\Models\Game;
use App\Models\GameRoom;
use App\Models\ProvablyFairGame;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract class MultiroomMultiplayerGameService
{
    private $user;
    private $room;
    private $provablyFairGame;
    private $game;
    private $gameable;
    private $isGameEventBroadcastingDisabled = FALSE;

    protected $gameableClass;

    public function __construct(User $user)
    {
        if (!$this->gameableClass)
            throw new \Exception('Gameable model should be explicitly set in the child class before calling MultiroomMultiplayerGameService constructor.');

        
        $this->user = $user->getKey() ? $user : auth()->user();
    }

    private function init(): MultiroomMultiplayerGameService
    {
        
        $otherPlayer = $this->room->activePlayers->first();

        
        if ($otherPlayer) {
            $this->provablyFairGame = $otherPlayer->game->provablyFairGame;
            $this->gameable = $otherPlayer->game->gameable;
        }

        return $this;
    }

    
    public final function createGame(): MultiroomMultiplayerGameService
    {
        $this->init();

        if (!$this->getProvablyFairGame()) {
            $this->createProvablyFairGame();
        }

        if (!$this->getGameable()) {
            $this->createGameable();
        }

        
        $this->game = new Game();
        $this->game->account()->associate($this->getUserAccount());
        $this->game->provablyFairGame()->associate($this->getProvablyFairGame());
        $this->game->bet = $this->getRoom()->parameters->bet;
        $this->game->win = 0;
        $this->game->status = Game::STATUS_IN_PROGRESS;
        $this->getGameable()->game()->save($this->game);

        $this->game->setRelation('gameable', $this->getGameable());

        
        $player = $this->room->player($this->getUser());
        $player->game()->associate($this->game);
        $player->save();

        
        AccountTransaction::create($this->getUserAccount(), $this->game, -$this->game->bet);

        return $this;
    }

    
    public final function loadGame(): MultiroomMultiplayerGameService
    {
        $player = $this->room->player($this->getUser());

        $this->game = $player->game;
        $this->provablyFairGame = $this->game->provablyFairGame;
        $this->gameable = $this->game->gameable;

        return $this;
    }

    
    protected final function completeGame(Game $game, array $attributes): MultiroomMultiplayerGameService
    {
        
        if ($game->id == $this->game->id) {
            $game = $this->game;
        }

        
        $player = $this->room->player($game);
        $player->game()->dissociate();
        $player->save();

        
        foreach ($attributes as $key => $value) {
            $game->{$key} = $value;
        }
        $game->is_completed = TRUE;

        
        $game->save();

        
        if ($game->is_completed && $game->win > 0) {
            AccountTransaction::create($game->account, $game, $game->win); 
        }

        
        event(new GamePlayed($game));

        return $this;
    }

    
    protected final function cancelGame(Game $game, array $attributes): MultiroomMultiplayerGameService
    {
        
        if ($game->id == $this->game->id) {
            $game = $this->game;
        }

        
        $player = $this->room->player($game);
        $player->game()->dissociate();
        $player->save();

        
        foreach ($attributes as $key => $value) {
            $game->{$key} = $value;
        }
        $game->is_cancelled = TRUE;

        
        $game->save();

        
        AccountTransaction::create($game->account, $game, $game->bet); 

        return $this;
    }

    
    public final function createProvablyFairGame(string $clientSeed = NULL): MultiroomMultiplayerGameService
    {
        $this->provablyFairGame = new ProvablyFairGame();
        $this->provablyFairGame->secret = $this->makeSecret();
        $this->provablyFairGame->client_seed = $clientSeed ?: random_int(10000000, 99999999);
        $this->provablyFairGame->gameable_type = $this->gameableClass;
        $this->provablyFairGame->save();

        return $this;
    }

    
    public final function getProvablyFairGame(): ?ProvablyFairGame
    {
        return $this->provablyFairGame;
    }

    
    public final function getRoom(): ?GameRoom
    {
        return $this->room;
    }

    
    public final function setRoom(GameRoom $room): MultiroomMultiplayerGameService
    {
        $this->room = $room;

        return $this;
    }

    
    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function getGameable(): ?Model
    {
        return $this->gameable;
    }

    public function setGameable(Model $gameable): MultiroomMultiplayerGameService
    {
        $this->gameable = $gameable;

        return $this;
    }

    public function isGameCompleted(): bool
    {
        return $this->getGame()->is_completed ?? FALSE;
    }

    public function isGameCancelled(): bool
    {
        return $this->getGame()->is_cancelled ?? FALSE;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getUserAccount(): Account
    {
        return $this->getUser()->account;
    }

    
    protected final function broadcastGameEvent(array $event): MultiroomMultiplayerGameService
    {
        if (!$this->isGameEventBroadcastingDisabled) {
            event(new MultiroomMultiplayerGameAction($this->getRoom(), $event));
        }

        return $this;
    }

    protected final function disableGameEventBroadcasting(): MultiroomMultiplayerGameService
    {
        $this->isGameEventBroadcastingDisabled = TRUE;

        return $this;
    }

    
    abstract public function makeSecret(): string;

    
    abstract protected function createGameable(): MultiroomMultiplayerGameService;

    
    abstract public function action(string $action): MultiroomMultiplayerGameService;
}
