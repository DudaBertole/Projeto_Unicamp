<?php

namespace App\ValueObjects;

use InvalidArgumentException;

final class BirthDate
{
    private \DateTimeImmutable $date;

    public function __construct(\DateTimeImmutable $date)
    {
        if ($date > new \DateTimeImmutable('now')) {
            throw new InvalidArgumentException('Brith date must not be a future date.');
        }

        $this->date = $date;
    }

    /**
     * Creates the object from a string in Y-m-d format.
     */
    public static function fromString(string $dateString): self
    {
        try {
            $date = new \DateTimeImmutable($dateString);
            $date = $date->setTime(0, 0, 0);
        } catch (\Exception $e) {
            throw new InvalidArgumentException('Invalid date format. Use Y-m-d.', 0, $e);
        }

        return new self($date);
    }

    public function __toString(): string
    {
        return $this->date->format('Y-m-d');
    }

    public function calculateAge(): int
    {
        $hoje = new \DateTimeImmutable('now');
        $diferenca = $this->date->diff($hoje);
        return $diferenca->y;
    }

    /**
     * Formats the date for display (e.g., 25/10/1990).
     */
    public function formateToExibition(): string
    {
        return $this->date->format('d/m/Y');
    }
}
