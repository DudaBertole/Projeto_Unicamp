<?php

namespace App\Routes;

use App\Controllers\UserController;
use App\Controllers\HealthController;

return [
    ['GET',  '/health', [HealthController::class, 'health']],
    ['POST',  '/user/create', [UserController::class, 'create']],
    ['PATCH',  '/user/update', [UserController::class, 'update']],
    ['POST',  '/user/authenticate', [UserController::class, 'authenticate']]
];
