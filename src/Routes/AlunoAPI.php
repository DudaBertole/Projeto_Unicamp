<?php

namespace App\Routes;

use App\Controllers\Aluno\AlunoCreateController;
use App\Controllers\Aluno\AlunoUpdateController;
use App\Controllers\Aluno\AlunoDeleteController;
use App\Controllers\Aluno\AlunoListAllController;
use App\Controllers\Aluno\AlunoListByCursoController;
use App\Controllers\Aluno\AlunoFindByMatriculaController;
use App\Controllers\Aluno\AlunoFindByNomeController;

return [
    ['POST', '/aluno/create', [AlunoCreateController::class, 'create']],
    ['PUT',  '/aluno/update', [AlunoUpdateController::class, 'update']],
    ['DELETE', '/aluno/delete', [AlunoDeleteController::class, 'delete']],
    ['GET',  '/aluno/list', [AlunoListAllController::class, 'listAll']],
    ['GET',  '/aluno/list-by-curso', [AlunoListByCursoController::class, 'listByCurso']],
    ['GET',  '/aluno/find-by-matricula', [AlunoFindByMatriculaController::class, 'findByMatricula']],
    ['GET',  '/aluno/find-by-nome', [AlunoFindByNomeController::class, 'findByNome']],
];