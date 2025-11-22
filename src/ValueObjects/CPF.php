<?php

namespace App\ValueObjects;

use InvalidArgumentException;

final class CPF
{
    private string $cpf;

    public function __construct(string $cpf)
    {
        $this->cpf = $cpf;
    }

    public static function create(string $cpf): self
    {
        $cpf = self::sanitize($cpf);

        if (!preg_match('/^\d{11}$/', $cpf)) {
            throw new InvalidArgumentException('CPF must contain exactly 11 digits.');
        }

        if (!self::isValid($cpf)) {
            throw new InvalidArgumentException('Invalid CPF.');
        }

        return new self($cpf);
    }

    public static function sanitize(string $cpf): string
    {
        return preg_replace('/\D/', '', $cpf);
    }

    public static function isValid(string $cpf): bool
    {

        $cpf = self::sanitize($cpf);

        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        $base = substr($cpf, 0, 9);
        $checkDigits = substr($cpf, 9, 2);

        $d1 = self::calculateCheckDigit($base, 10);
        $d2 = self::calculateCheckDigit($base . $d1, 11);

        return $checkDigits === ((string)$d1 . (string)$d2);
    }

    private static function calculateCheckDigit(string $digits, int $weightStart): int
    {
        $sum = 0;

        for ($i = 0; $i < strlen($digits); $i++) {
            $sum += (int)$digits[$i] * ($weightStart - $i);
        }

        $remainder = $sum % 11;
        return $remainder < 2 ? 0 : 11 - $remainder;
    }

    public function equals(self $other): bool
    {
        return $this->cpf === $other->cpf;
    }

    public function __toString(): string
    {
        return $this->cpf;
    }

    public function getFormatted(): string
    {
        return sprintf(
            '%s.%s.%s-%s',
            substr($this->cpf, 0, 3),
            substr($this->cpf, 3, 3),
            substr($this->cpf, 6, 3),
            substr($this->cpf, 9, 2)
        );
    }
}
