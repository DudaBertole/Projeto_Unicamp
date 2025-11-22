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

    // public static function fromDateTimeImmutable(\DateTimeImmutable $dt): self
    // {
    //     return new self($dt);
    // }

    // public static function now(): self
    // {
    //     return new self(new \DateTimeImmutable('now'));
    // }

    // public function toDateTimeImmutable(): \DateTimeImmutable
    // {
    //     return $this->dateTime;
    // }

    /**
     * Retorna no formato MySQL 'Y-m-d H:i:s'
     */
    public function toMySQL(): string
    {
        return $this->dateTime->format('Y-m-d H:i:s');
    }

    public function __toString(): string
    {
        return $this->toMySQL();
    }

    // public function format(string $format): string
    // {
    //     return $this->dateTime->format($format);
    // }

    // /**
    //  * Retorna nova instância aplicando uma string de modify (ex: '+1 day', '-2 hours').
    //  */
    // public function modify(string $modify): self
    // {
    //     return new self($this->dateTime->modify($modify));
    // }

    // public function addSeconds(int $seconds): self
    // {
    //     return $this->modify(($seconds >= 0 ? '+' : '') . $seconds . ' seconds');
    // }

    // public function addMinutes(int $minutes): self
    // {
    //     return $this->modify(($minutes >= 0 ? '+' : '') . $minutes . ' minutes');
    // }

    // public function addHours(int $hours): self
    // {
    //     return $this->modify(($hours >= 0 ? '+' : '') . $hours . ' hours');
    // }

    // public function startOfDay(): self
    // {
    //     return new self($this->dateTime->setTime(0, 0, 0));
    // }

    // public function endOfDay(): self
    // {
    //     return new self($this->dateTime->setTime(23, 59, 59));
    // }

    // public function isBefore(self $other): bool
    // {
    //     return $this->dateTime < $other->toDateTimeImmutable();
    // }

    // public function isAfter(self $other): bool
    // {
    //     return $this->dateTime > $other->toDateTimeImmutable();
    // }

    // public function diffInSeconds(self $other): int
    // {
    //     return (int) abs($this->dateTime->getTimestamp() - $other->toDateTimeImmutable()->getTimestamp());
    // }
}
