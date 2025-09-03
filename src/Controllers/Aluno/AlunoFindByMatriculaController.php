<?php

namespace App\Controllers\Aluno;

use App\Repositories\Repositories_MySQL\AlunoRepositoryMySQL;

class AlunoFindByMatriculaController {
	public function findByMatricula() {
		$matricula = $_GET['matricula'] ?? null;
		if (!$matricula) {
			http_response_code(400);
			echo json_encode(['error' => 'Matricula não informada']);
			return;
		}
		$repo = new AlunoRepositoryMySQL();
		$aluno = $repo->findByMatricula($matricula);
		echo json_encode($aluno);
	}
}
