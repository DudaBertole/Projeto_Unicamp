<?php

namespace App\Controllers\Aluno;

use App\Repositories\Repositories_MySQL\AlunoRepositoryMySQL;

class AlunoListAllController {
	public function listAll() {
		$repo = new AlunoRepositoryMySQL();
		$alunos = $repo->listAll();
		echo json_encode($alunos);
	}
}
