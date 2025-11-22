<?php

namespace App\Routes;

use App\Controllers\HealthController;
use App\Controllers\UserController;
use App\Controllers\GameController;

return [
    ['GET',  '/health', [HealthController::class, 'health']],

    ['POST',  '/user/create', [UserController::class, 'create']],
    ['PATCH',  '/user/update', [UserController::class, 'update']],
    ['POST',  '/user/authenticate', [UserController::class, 'authenticate']],
    ['GET',  '/user/findByID', [UserController::class, 'findByID']],
    ['GET',  '/user/findByCPF', [UserController::class, 'findByCPF']],

    ['POST',  '/game/create', [GameController::class, 'create']],
    ['GET',  '/game/listAll', [GameController::class, 'listAll']],
    ['GET',  '/game/listByGameMode', [GameController::class, 'listByGameMode']],
    ['GET',  '/game/listByUser', [GameController::class, 'listByUser']],
    ['GET',  '/game/findByID', [GameController::class, 'findByID']],

];
