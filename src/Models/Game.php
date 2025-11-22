<?php

namespace App\Models;

use App\Utils\GameMode;
use App\Utils\GameResult;
use App\ValueObjects\DateTime;

class Game
{
    private int $id;
    private int $user_id;
    private int $board_size;
    private int $moves_count;
    private GameMode $mode;
    private int $duration_seconds;
    private GameResult $result;
    private DateTime $datetime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getBoardSize(): int
    {
        return $this->board_size;
    }

    public function setBoardSize(int $board_size): self
    {
        $this->board_size = $board_size;
        return $this;
    }

    public function getMovesCount(): int
    {
        return $this->moves_count;
    }

    public function setMovesCount(int $moves_count): self
    {
        $this->moves_count = $moves_count;
        return $this;
    }

    public function getMode(): GameMode
    {
        return $this->mode;
    }

    public function setMode(GameMode $mode): self
    {
        $this->mode = $mode;
        return $this;
    }

    public function getDurationSeconds(): int
    {
        return $this->duration_seconds;
    }

    public function setDurationSeconds(int $duration_seconds): self
    {
        $this->duration_seconds = $duration_seconds;
        return $this;
    }

    public function getResult(): GameResult
    {
        return $this->result;
    }

    public function setResult(GameResult $result): self
    {
        $this->result = $result;
        return $this;
    }

    public function getdatetime(): DateTime
    {
        return $this->datetime;
    }

    public function setDateTime(DateTime $datetime): self
    {
        $this->datetime = $datetime;
        return $this;
    }
}
