<?php

namespace App\Controllers\Aluno;

use App\Repositories\Repositories_MySQL\AlunoRepositoryMySQL;

class AlunoDeleteController {
	public function delete() {
		$data = json_decode(file_get_contents('php://input'), true);
		if (!isset($data['matricula'])) {
			http_response_code(400);
			echo json_encode(['error' => 'Matricula não informada']);
			return;
		}
		$repo = new AlunoRepositoryMySQL();
		$repo->delete((int)$data['matricula']);
		http_response_code(200);
		echo json_encode(['message' => 'Aluno deletado com sucesso']);
	}
}
