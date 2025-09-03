<?php

namespace App\Models;

class Aluno implements \JsonSerializable {
    private int $matricula;
    private string $nome;
    private string $curso;
    private int $ano_ingresso;


    public function __construct(
        int $matricula,
        string $nome,
        string $curso,
        int $ano_ingresso
    ) {
        $this->matricula = $matricula;
        $this->nome = $nome;
        $this->curso = $curso;
        $this->ano_ingresso = $ano_ingresso;
    }

    public function getMatricula(): int {
        return $this->matricula;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getCurso(): string {
        return $this->curso;
    }

    public function getAnoIngresso(): int {
        return $this->ano_ingresso;
    }

    public function jsonSerialize(): array {
        return [
            'matricula' => $this->getMatricula(),
            'nome' => $this->getNome(),
            'curso' => $this->getCurso(),
            'ano_ingresso' => $this->getAnoIngresso()
        ];
    }

}