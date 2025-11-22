<?php

namespace App\Repositories\Repositories_MySQL;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\ValueObjects\Email;
use App\ValueObjects\Phone;
use App\ValueObjects\CPF;
use App\ValueObjects\BirthDate;
use PDO;

class UserRepositoryMySQL implements UserRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = MySQLConnection::getInstance()->getConnection();
    }

    public function create(User $user): void
    {
        $stmt = $this->connection->prepare("
            INSERT INTO users (
                full_name,
                birth_date,
                cpf,
                phone,
                username,
                email,
                password
            ) VALUES (
                :user_full_name,
                :user_birth_date,
                :user_cpf,
                :user_phone,
                :user_username,
                :user_email,
                :user_password
            )
        ");
        $stmt->execute([
            ':user_full_name' => $user->getFullName(),
            ':user_birth_date' => $user->getBirthDate(),
            ':user_cpf' => $user->getCpf(),
            ':user_phone' => $user->getPhone(),
            ':user_username' => $user->getUsername(),
            ':user_email' => $user->getEmail(),
            ':user_password' => $user->getPassword(),
        ]);
    }

    public function update(User $user): void
    {
        $stmt = $this->connection->prepare("
            UPDATE users SET
                full_name = :user_full_name,
                birth_date = :user_birth_date,
                cpf = :user_cpf,
                phone = :user_phone,
                username = :user_username,
                email = :user_email,
                password = :user_password
            WHERE id = :user_id
        ");
        $stmt->execute([
            ':user_full_name' => $user->getFullName(),
            ':user_birth_date' => $user->getBirthDate(),
            ':user_cpf' => $user->getCpf(),
            ':user_phone' => $user->getPhone(),
            ':user_username' => $user->getUsername(),
            ':user_email' => $user->getEmail(),
            ':user_password' => $user->getPassword(),
            ':user_id' => $user->getId(),
        ]);
    }

    public function list(): ?array
    {
        $stmt = $this->connection->query("
            SELECT
                id,
                full_name,
                birth_date,
                cpf,
                phone,
                username,
                email,
                password
            FROM users
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) {
            return null;
        }

        $users = [];
        foreach ($rows as $row) {
            $users[] = $this->mapRowToUser($row);
        }

        return $users;
    }

    public function findByID(int $id): ?User
    {
        $stmt = $this->connection->prepare("
            SELECT
                id,
                full_name,
                birth_date,
                cpf,
                phone,
                username,
                email,
                password
            FROM users
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->mapRowToUser($row);
    }

    public function findByCPF(CPF $cpf): ?User
    {
        $stmt = $this->connection->prepare("
            SELECT
                id,
                full_name,
                birth_date,
                cpf,
                phone,
                username,
                email,
                password
            FROM users
            WHERE cpf = :cpf
            LIMIT 1
        ");
        $stmt->execute([':cpf' => $cpf]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->mapRowToUser($row);
    }

    private function mapRowToUser(array $row): User
    {
        $user = new User();
        $user->setId(isset($row['id']) ? (int)$row['id'] : null);
        $user->setFullName($row['full_name'] ?? '');

        try {
            $birthDateValue = $row['birth_date'] ?? null;
            $date = $birthDateValue ? new \DateTimeImmutable($birthDateValue) : new \DateTimeImmutable('1970-01-01');
            $user->setBirthDate(new BirthDate($date));
        } catch (\Throwable $e) {
            $user->setBirthDate(new BirthDate(new \DateTimeImmutable('1970-01-01')));
        }

        try {
            $user->setCpf(new CPF($row['cpf'] ?? ''));
        } catch (\Throwable $e) {
            $user->setCpf(new CPF(''));
        }

        try {
            $user->setPhone(new Phone($row['phone'] ?? ''));
        } catch (\Throwable $e) {
            $user->setPhone(new Phone(''));
        }

        $user->setUsername($row['username'] ?? '');

        try {
            $user->setEmail(new Email($row['email'] ?? ''));
        } catch (\Throwable $e) {
            $user->setEmail(new Email(''));
        }

        $user->setPassword($row['password'] ?? '');

        return $user;
    }

    public function authenticate(string $username, string $password): bool
    {
        $stmt = $this->connection->prepare("
            SELECT password
            FROM users
            WHERE username = :username
            LIMIT 1
        ");
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || empty($row['password'])) {
            return false;
        }

        return password_verify($password, $row['password']);
    }
}
