<?php

namespace App\Controllers\Aluno;

use App\Models\Aluno;
use App\Repositories\Repositories_MySQL\AlunoRepositoryMySQL;

class AlunoCreateController {
	public function create() {
		$data = json_decode(file_get_contents('php://input'), true);
		if (!isset($data['matricula'], $data['nome'], $data['curso'], $data['ano_ingresso'])) {
			http_response_code(400);
			echo json_encode(['error' => 'Dados incompletos']);
			return;
		}
		$aluno = new Aluno(
			(int)$data['matricula'],
			$data['nome'],
			$data['curso'],
			(int)$data['ano_ingresso']
		);
		$repo = new AlunoRepositoryMySQL();
		$repo->create($aluno);
		http_response_code(201);
		echo json_encode(['message' => 'Aluno criado com sucesso']);
	}
}
