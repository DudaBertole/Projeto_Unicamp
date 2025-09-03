<?php

namespace App\Controllers\Aluno;

use App\Repositories\Repositories_MySQL\AlunoRepositoryMySQL;

class AlunoListByCursoController {
	public function listByCurso() {
		$curso = $_GET['curso'] ?? null;
		if (!$curso) {
			http_response_code(400);
			echo json_encode(['error' => 'Curso não informado']);
			return;
		}
		$repo = new AlunoRepositoryMySQL();
		$alunos = $repo->listByCurso($curso);
		echo json_encode($alunos);
	}
}
