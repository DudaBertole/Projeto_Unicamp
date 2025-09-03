<?php

namespace App\Controllers\Aluno;

use App\Repositories\Repositories_MySQL\AlunoRepositoryMySQL;

class AlunoFindByNomeController {
	public function findByNome() {
		$nome = $_GET['nome'] ?? null;
		if (!$nome) {
			http_response_code(400);
			echo json_encode(['error' => 'Nome não informado']);
			return;
		}
		$repo = new AlunoRepositoryMySQL();
		$aluno = $repo->findByNome($nome);
		echo json_encode($aluno);
	}
}
