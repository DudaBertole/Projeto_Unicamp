<?php

namespace App\Models;

use App\ValueObjects\Email;
use App\ValueObjects\Phone;
use App\ValueObjects\CPF;
use App\ValueObjects\BirthDate;

class User
{
    private $id;
    private string $full_name;
    private BirthDate $birth_date;
    private CPF $cpf;
    private Phone $phone;
    private string $username;
    private Email $email;
    private string $password;

    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getFullName(): string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): void
    {
        $this->full_name = $full_name;
    }

    public function getBirthDate(): BirthDate
    {
        return $this->birth_date;
    }

    public function setBirthDate(BirthDate $birth_date): void
    {
        $this->birth_date = $birth_date;
    }

    public function getCpf(): CPF
    {
        return $this->cpf;
    }

    public function setCpf(CPF $cpf): void
    {
        $this->cpf = $cpf;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function setPhone(Phone $phone): void
    {
        $this->phone = $phone;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
