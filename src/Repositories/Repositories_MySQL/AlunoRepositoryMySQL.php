<?php

namespace App\Repositories\Repositories_MySQL;

use App\Models\Aluno;
use App\Repositories\Interfaces\AlunoRepositoryInterface;
use PDO;

class AlunoRepositoryMySQL implements AlunoRepositoryInterface 
{
    private PDO $conn;

    public function __construct() {
        $this->conn = MySQLConnection::getInstance()->getConnection();
    }

    public function create(Aluno $aluno): void {
        $sql = "INSERT INTO aluno (matricula, nome, curso, ano_ingresso) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $aluno->getMatricula(),
            $aluno->getNome(),
            $aluno->getCurso(),
            $aluno->getAnoIngresso()
        ]);
    }

    public function update(Aluno $aluno): void {
        $sql = "UPDATE aluno SET nome=?, curso=?, ano_ingresso=? WHERE matricula=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $aluno->getNome(),
            $aluno->getCurso(),
            $aluno->getAnoIngresso(),
            $aluno->getMatricula()
        ]);
    }

    public function delete(int $matricula): void {
        $sql = "DELETE FROM aluno WHERE matricula=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$matricula]);
    }

    public function listAll(): ?array {
        $sql = "SELECT * FROM aluno";
        $stmt = $this->conn->query($sql);
        $result = $this->mapAlunos($stmt->fetchAll());
        return $result ?: null;
    }

    public function findByMatricula(string $matricula): ?Aluno {
        $sql = "SELECT * FROM aluno WHERE matricula=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$matricula]);
        $row = $stmt->fetch();
        return $row ? $this->mapAluno($row) : null;
    }

    public function listByCurso(string $curso): ?array {
        $sql = "SELECT * FROM aluno WHERE curso=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$curso]);
        $result = $this->mapAlunos($stmt->fetchAll());
        return $result ?: null;
    }

    public function findByNome(string $nome): ?Aluno {
        $sql = "SELECT * FROM aluno WHERE nome LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['%' . $nome . '%']);
        $row = $stmt->fetch();
        return $row ? $this->mapAluno($row) : null;
    }

    /** ---------------------- Helpers ---------------------- */

    private function mapAluno(array $row): Aluno {
        return new Aluno(
            $row['matricula'],
            $row['nome'],
            $row['curso'],
            $row['ano_ingresso']
        );
    }

    private function mapAlunos(array $rows): array {
        return array_map(fn($row) => $this->mapAluno($row), $rows);
    }
}
