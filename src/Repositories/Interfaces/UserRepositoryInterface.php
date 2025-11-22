<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use App\ValueObjects\CPF;

interface UserRepositoryInterface
{
    public function create(User $user): void;
    public function update(User $user): void;
    public function authenticate(string $username, string $password): bool;

    /**
     * @return User[]
     */
    public function list(): ?array;

    public function findByCPF(CPF $cpf): ?User;
    public function findByID(int $id): ?User;
}
