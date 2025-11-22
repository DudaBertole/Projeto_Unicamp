<?php

namespace App\Controllers;

use App\Repositories\Repositories_MySQL\UserRepositoryMySQL;
use App\Models\User;
use App\ValueObjects\Email;
use App\ValueObjects\Phone;
use App\ValueObjects\CPF;
use App\ValueObjects\BirthDate;
use InvalidArgumentException;

class UserController
{

    private function getJsonBody(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    private function jsonResponse($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    private function repo(): UserRepositoryMySQL
    {
        return new UserRepositoryMySQL();
    }

    public function create(): void
    {
        $data = $this->getJsonBody();

        $required = ['full_name', 'birth_date', 'cpf', 'phone', 'username', 'email', 'password'];
        foreach ($required as $field) {
            if (empty($data[$field]) && $data[$field] !== '0') {
                $this->jsonResponse(['error' => "Obrigatory field missing: $field"], 400);
                return;
            }
        }

        try {
            $email = Email::create($data['email']);
        } catch (InvalidArgumentException $e) {
            $this->jsonResponse(['error' => 'Invalid email: ' . $e->getMessage()], 400);
            return;
        } catch (\Throwable $e) {
            $this->jsonResponse(['error' => 'Error validating email. ' . $e->getMessage()], 400);
            return;
        }

        try {
            $phone = Phone::create($data['phone']);
        } catch (InvalidArgumentException $e) {
            $this->jsonResponse(['error' => 'Invalid phone: ' . $e->getMessage()], 400);
            return;
        } catch (\Throwable $e) {
            $this->jsonResponse(['error' => 'Error validating phone.'], 400);
            return;
        }

        try {
            $cpf = CPF::create($data['cpf']);
        } catch (InvalidArgumentException $e) {
            $this->jsonResponse(['error' => 'Invalid CPF: ' . $e->getMessage()], 400);
            return;
        } catch (\Throwable $e) {
            $this->jsonResponse(['error' => 'Error validating CPF.'], 400);
            return;
        }

        try {
            $birthDate = BirthDate::fromString($data['birth_date']);
        } catch (InvalidArgumentException $e) {
            $this->jsonResponse(['error' => 'Invalid birth date: ' . $e->getMessage()], 400);
            return;
        } catch (\Throwable $e) {
            $this->jsonResponse(['error' => 'Error validating birth date.'], 400);
            return;
        }

        $user = new User();
        $user->setFullName((string)$data['full_name']);
        $user->setBirthDate($birthDate);
        $user->setCpf($cpf);
        $user->setPhone($phone);
        $user->setUsername((string)$data['username']);
        $user->setEmail($email);
        $user->setPassword(password_hash((string)$data['password'], PASSWORD_DEFAULT));

        try {
            $this->repo()->create($user);
        } catch (\Throwable $e) {
            $this->jsonResponse(['error' => 'Error saving user: ' . $e->getMessage()], 500);
            return;
        }

        $this->jsonResponse(['message' => 'User created successfully.'], 201);
    }

    public function update(): void
    {
        $data = $this->getJsonBody();

        if (empty($data['cpf'])) {
            $this->jsonResponse(['error' => "Obrigatory field missing: cpf."], 400);
            return;
        }

        try {
            $cpf = CPF::create($data['cpf']);
        } catch (InvalidArgumentException $e) {
            $this->jsonResponse(['error' => 'Invalid CPF: ' . $e->getMessage()], 400);
            return;
        } catch (\Throwable $e) {
            $this->jsonResponse(['error' => 'Error validating CPF: ' . $e->getMessage()], 400);
            return;
        }

        try {
            $user = $this->repo()->findByCPF($cpf);
        } catch (\Throwable $e) {
            $this->jsonResponse(['error' => 'Error fetching user: ' . $e->getMessage()], 500);
            return;
        }

        if (!$user) {
            $this->jsonResponse(['error' => 'User not found'], 404);
            return;
        }

        if (array_key_exists('full_name', $data)) {
            $user->setFullName((string)$data['full_name']);
        }

        if (array_key_exists('birth_date', $data)) {
            try {
                $birthDate = BirthDate::fromString($data['birth_date']);
                $user->setBirthDate($birthDate);
            } catch (InvalidArgumentException $e) {
                $this->jsonResponse(['error' => 'Invalid birth date: ' . $e->getMessage()], 400);
                return;
            } catch (\Throwable $e) {
                $this->jsonResponse(['error' => 'Error validating birth date: ' . $e->getMessage()], 400);
                return;
            }
        }

        if (array_key_exists('cpf', $data)) {
            try {
                $cpf = CPF::create($data['cpf']);
                $user->setCpf($cpf);
            } catch (InvalidArgumentException $e) {
                $this->jsonResponse(['error' => 'Invalid CPF: ' . $e->getMessage()], 400);
                return;
            } catch (\Throwable $e) {
                $this->jsonResponse(['error' => 'Error validating CPF: ' . $e->getMessage()], 400);
                return;
            }
        }

        if (array_key_exists('phone', $data)) {
            try {
                $phone = Phone::create($data['phone']);
                $user->setPhone($phone);
            } catch (InvalidArgumentException $e) {
                $this->jsonResponse(['error' => 'Invalid phone: ' . $e->getMessage()], 400);
                return;
            } catch (\Throwable $e) {
                $this->jsonResponse(['error' => 'Error validating phone: ' . $e->getMessage()], 400);
                return;
            }
        }

        if (array_key_exists('username', $data)) {
            $user->setUsername((string)$data['username']);
        }

        if (array_key_exists('email', $data)) {
            try {
                $email = Email::create($data['email']);
                $user->setEmail($email);
            } catch (InvalidArgumentException $e) {
                $this->jsonResponse(['error' => 'Invalid email: ' . $e->getMessage()], 400);
                return;
            } catch (\Throwable $e) {
                $this->jsonResponse(['error' => 'Error validating email: ' . $e->getMessage()], 400);
                return;
            }
        }

        if (array_key_exists('password', $data)) {
            $user->setPassword(
                password_hash((string)$data['password'], PASSWORD_DEFAULT)
            );
        }

        try {
            $this->repo()->update($user);
        } catch (\Throwable $e) {
            $this->jsonResponse(['error' => 'Error updating user: ' . $e->getMessage()], 500);
            return;
        }

        $this->jsonResponse(['message' => 'User updated successfully.'], 200);
    }

    public function authenticate(): void
    {
        $data = $this->getJsonBody();

        if (empty($data['username']) && $data['username'] !== '0') {
            $this->jsonResponse(['error' => "Obrigatory field missing: username"], 400);
            return;
        }

        if (!isset($data['password']) || ($data['password'] === '' && $data['password'] !== '0')) {
            $this->jsonResponse(['error' => "Obrigatory field missing: password"], 400);
            return;
        }

        $username = (string)$data['username'];
        $password = (string)$data['password'];

        try {
            $authenticated = $this->repo()->authenticate($username, $password);
        } catch (\Throwable $e) {
            $this->jsonResponse(['error' => 'Error authenticating: ' . $e->getMessage()], 500);
            return;
        }

        if (!$authenticated) {
            $this->jsonResponse(['error' => 'Invalid credentials.'], 401);
            return;
        }

        $this->jsonResponse(['message' => 'Authenticated successfully.'], 200);
    }

    public function findByID(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id === null || $id === '') {
            $this->jsonResponse(['error' => 'id is required.'], 400);
            return;
        }

        try {
            $user = $this->repo()->findByID((int)$id);
        } catch (\Throwable $e) {
            $this->jsonResponse(['error' => 'Failed to fetch user: ' . $e->getMessage()], 500);
            return;
        }

        if (!$user) {
            $this->jsonResponse(['error' => 'User not found.'], 404);
            return;
        }

        $this->jsonResponse($this->userToArray($user));
    }

    public function findByCPF(): void
    {
        $cpf = $_GET['cpf'] ?? null;
        if ($cpf === null || $cpf === '') {
            $this->jsonResponse(['error' => 'cpf is required.'], 400);
            return;
        }

        try {
            $cpfVo = new CPF($cpf);
        } catch (\Throwable $e) {
            $this->jsonResponse(['error' => 'Invalid CPF format: ' . $e->getMessage()], 400);
            return;
        }

        try {
            $user = $this->repo()->findByCPF($cpfVo);
        } catch (\Throwable $e) {
            $this->jsonResponse(['error' => 'Failed to fetch user: ' . $e->getMessage()], 500);
            return;
        }

        if (!$user) {
            $this->jsonResponse(['error' => 'User not found.'], 404);
            return;
        }

        $this->jsonResponse($this->userToArray($user));
    }

    private function userToArray(User $user): array
    {
        return [
            'id' => $user->getId(),
            'full_name' => $user->getFullName(),
            'birth_date' => (string)$user->getBirthDate(),
            'cpf' => (string)$user->getCpf(),
            'phone' => (string)$user->getPhone(),
            'username' => $user->getUsername(),
            'email' => (string)$user->getEmail(),
            // intentionally not returning password
        ];
    }
}
