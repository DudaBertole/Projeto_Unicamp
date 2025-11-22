<?php

namespace App\ValueObjects;

use InvalidArgumentException;

final class DateTime
{
    private \DateTimeImmutable $dateTime;

    public function __construct(\DateTimeImmutable $dateTime)
    {
        $this->dateTime = $dateTime;
    }


    public static function fromString(string $dateTimeString): self
    {
        // acepts 'YYYY-MM-DD'
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTimeString)) {
            $dateTimeString .= ' 00:00:00';
        }

        // acepts 'YYYY-MM-DD HH:MM:SS' or anny other valid DateTimeImmutable format
        try {
            $dt = new \DateTimeImmutable($dateTimeString);
        } catch (\Exception $e) {
            throw new InvalidArgumentException('Invalid datetime format. Use Y-m-d or Y-m-d H:i:s.', 0, $e);
        }

        return new self($dt);
    }

    public function __toString(): string
    {
        return $this->dateTime->format('Y-m-d H:i:s');
    }
}
