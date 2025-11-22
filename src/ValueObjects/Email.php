<?php

namespace App\ValueObjects;

use InvalidArgumentException;

final class Email
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function create(string $value): self
    {
        $value = self::sanitize($value);

        if (!self::isValid($value)) {
            throw new InvalidArgumentException("Invalid e-mail: $value");
        }

        return new self($value);
    }

    private static function sanitize(string $value): string
    {
        // Remove UTF-8 BOM if present
        $value = preg_replace('/\x{FEFF}/u', '', $value);

        // Replace non-breaking space (UTF-8) with normal space
        $value = str_replace("\xC2\xA0", ' ', $value);

        // Remove other control characters
        $value = preg_replace('/[[:cntrl:]]+/', '', $value);

        // Use PHP filter to sanitize email (removes illegal characters)
        $value = filter_var($value, FILTER_SANITIZE_EMAIL);

        return trim($value);
    }

    public static function isValid(string $value): bool
    {
        return (bool) filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
