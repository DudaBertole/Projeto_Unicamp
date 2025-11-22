<?php

namespace App\ValueObjects;

use InvalidArgumentException;

final class Phone
{
    private string $value;

    public function __construct(string $phone)
    {
        $this->value = $phone;
    }

    public static function create(string $phone): self
    {
        $phone = self::sanitize($phone);

        if (!preg_match('/^\d{11}$/', $phone)) {
            throw new InvalidArgumentException('Phone number must contain exactly 11 digits. Brazilian DDD + 9 + number.');
        }

        if (!self::isValid($phone)) {
            throw new InvalidArgumentException("Invalid brazilian phone number.");
        }

        return new self($phone);
    }

    public static function sanitize(string $phone): string
    {
        return preg_replace('/\D/', '', $phone);
    }

    public static function isValid(string $phone): bool
    {
        $phone = self::sanitize($phone);

        $cleaned = self::sanitize($phone);
        // Brazilian mobile numbers: DDD (2 digits 11-99), first digit after DDD is 9, plus 8 digits
        return (bool) preg_match('/^([1-9][1-9])9\d{8}$/', $cleaned);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getFormatted(): string
    {
        $value = $this->value;

        if (preg_match('/^(\d{2})(\d{5})(\d{4})$/', $value, $matches)) {
            $value = sprintf('(%s) %s-%s', $matches[1], $matches[2], $matches[3]);
        }
        return $value;
    }
}
