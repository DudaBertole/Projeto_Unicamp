<?php

namespace App\Repositories\Repositories_MySQL;

use App\Repositories\Interfaces\GameRepositoryInterface;
use App\Models\Game;
use App\Utils\GameMode;
use App\Utils\GameResult;
use App\ValueObjects\DateTime;
use PDO;

class GameRepositoryMySQL implements GameRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = MySQLConnection::getInstance()->getConnection();
    }

    public function create(Game $game): void
    {
        $stmt = $this->connection->prepare("
            INSERT INTO games (
                user_id,
                board_size,
                moves_count,
                mode,
                duration_seconds,
                result,
                play_datetime
            ) VALUES (
                :user_id,
                :board_size,
                :moves_count,
                :mode,
                :duration_seconds,
                :result,
                :play_datetime
            )
        ");

        $stmt->execute([
            ':user_id' => $game->getUserId(),
            ':board_size' => $game->getBoardSize(),
            ':moves_count' => $game->getMovesCount(),
            ':mode' => $game->getMode()->value,
            ':duration_seconds' => $game->getDurationSeconds(),
            ':result' => $game->getResult()->value,
            ':play_datetime' => (string)$game->getDateTime(),
        ]);
    }

    public function listAll(): ?array
    {
        $stmt = $this->connection->query("
            SELECT
                id,
                user_id,
                board_size,
                moves_count,
                mode,
                duration_seconds,
                result,
                play_datetime
            FROM games
            ORDER BY play_datetime DESC
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) {
            return null;
        }

        $games = [];
        foreach ($rows as $row) {
            $games[] = $this->mapRowToGame($row);
        }

        return $games;
    }

    public function listByGameMode(GameMode $game_mode): ?array
    {
        $stmt = $this->connection->prepare("
            SELECT
                id,
                user_id,
                board_size,
                moves_count,
                mode,
                duration_seconds,
                result,
                play_datetime
            FROM games
            WHERE mode = :mode
            ORDER BY play_datetime DESC
        ");
        $stmt->execute([':mode' => $game_mode->value]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) {
            return null;
        }

        $games = [];
        foreach ($rows as $row) {
            $games[] = $this->mapRowToGame($row);
        }

        return $games;
    }

    public function listByUser(int $user_id, ?GameMode $game_mode = null): ?array
    {
        if ($game_mode === null) {
            $stmt = $this->connection->prepare("
                SELECT
                    id,
                    user_id,
                    board_size,
                    moves_count,
                    mode,
                    duration_seconds,
                    result,
                    play_datetime
                FROM games
                WHERE user_id = :user_id
                ORDER BY play_datetime DESC
            ");
            $stmt->execute([':user_id' => $user_id]);
        } else {
            $stmt = $this->connection->prepare("
                SELECT
                    id,
                    user_id,
                    board_size,
                    moves_count,
                    mode,
                    duration_seconds,
                    result,
                    play_datetime
                FROM games
                WHERE user_id = :user_id AND mode = :mode
                ORDER BY play_datetime DESC
            ");
            $stmt->execute([
                ':user_id' => $user_id,
                ':mode' => $game_mode->value,
            ]);
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) {
            return null;
        }

        $games = [];
        foreach ($rows as $row) {
            $games[] = $this->mapRowToGame($row);
        }

        return $games;
    }

    public function findByID(int $id): ?Game
    {
        $stmt = $this->connection->prepare("
            SELECT
                id,
                user_id,
                board_size,
                moves_count,
                mode,
                duration_seconds,
                result,
                play_datetime
            FROM games
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->mapRowToGame($row);
    }

    private function mapRowToGame(array $row): Game
    {
        $game = new Game();

        $game->setId(isset($row['id']) ? (int)$row['id'] : null);
        $game->setUserId(isset($row['user_id']) ? (int)$row['user_id'] : 0);
        $game->setBoardSize(isset($row['board_size']) ? (int)$row['board_size'] : 0);
        $game->setMovesCount(isset($row['moves_count']) ? (int)$row['moves_count'] : 0);

        try {
            $game->setMode(isset($row['mode']) ? GameMode::from($row['mode']) : GameMode::CLASSIC);
        } catch (\Throwable $e) {
            $game->setMode(GameMode::CLASSIC);
        }

        $game->setDurationSeconds(isset($row['duration_seconds']) ? (int)$row['duration_seconds'] : 0);

        try {
            $game->setResult(isset($row['result']) ? GameResult::from($row['result']) : GameResult::LOSS);
        } catch (\Throwable $e) {
            $game->setResult(GameResult::LOSS);
        }

        try {
            $dtValue = $row['play_datetime'] ?? null;
            $date = $dtValue ? new \DateTimeImmutable($dtValue) : new \DateTimeImmutable('1970-01-01 00:00:00');
            $game->setDateTime(new DateTime($date));
        } catch (\Throwable $e) {
            $game->setDateTime(new DateTime(new \DateTimeImmutable('1970-01-01 00:00:00')));
        }

        return $game;
    }
}
