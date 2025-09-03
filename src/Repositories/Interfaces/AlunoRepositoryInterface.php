<?php

namespace App\Repositories\Interfaces;

use App\Models\Aluno;

interface AlunoRepositoryInterface {
    public function create(Aluno $aluno): void;
    public function update(Aluno $aluno): void;
    public function delete(int $matricula): void;

    /**
     * @return Aluno[]
     */
    public function listAll(): ?array;
    
    /**
     * @return Aluno[]
     */
    public function listByCurso(string $curso): ?array;

    public function findByMatricula(string $matricula): ?Aluno;
    public function findByNome(string $nome): ?Aluno;
}