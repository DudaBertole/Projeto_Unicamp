<?php

namespace App\Repositories\Interfaces;

use App\Models\Game;
use App\Utils\GameMode;

interface GameRepositoryInterface
{
    public function create(Game $user): void;

    /**
     * @return Game[]
     */
    public function listAll(): ?array;

    /**
     * @return Game[]
     */
    public function listByGameMode(GameMode $game_mode): ?array;

    /**
     * @return Game[]
     */
    public function listByUser(int $user_id, ?GameMode $game_mode = null): ?array;

    public function findByID(int $id): ?Game;
}
