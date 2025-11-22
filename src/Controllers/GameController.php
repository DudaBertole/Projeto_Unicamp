<?php

namespace App\Controllers;

use App\Repositories\Repositories_MySQL\GameRepositoryMySQL;
use App\Models\Game;
use App\Utils\GameMode;
use App\Utils\GameResult;
use App\ValueObjects\DateTime;


class GameController
{

    private function getJsonBody(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    private function jsonResponse($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    private function repo(): GameRepositoryMySQL
    {
        return new GameRepositoryMySQL();
    }

    public function create(): void
    {
        $data = $this->getJsonBody();

        $required = ['user_id', 'board_size', 'moves_count', 'mode', 'duration_seconds', 'result', 'datetime'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                $this->jsonResponse(['error' => "Field '{$field}' is required."], 400);
                return;
            }
        }

        try {
            $mode = GameMode::from($data['mode']);
        } catch (\Throwable $e) {
            $this->jsonResponse(['error' => 'Invalid game mode: ' . $e->getMessage()], 400);
            return;
        }

        try {
            $result = GameResult::from($data['result']);
        } catch (\Throwable $e) {
            $this->jsonResponse(['error' => 'Invalid game result: ' . $e->getMessage()], 400);
            return;
        }

        try {
            $dtString = $data['datetime'] ?? null;
            $dt = $dtString ? new \DateTimeImmutable($dtString) : new \DateTimeImmutable('now');
            $voDate = new DateTime($dt);
        } catch (\Throwable $e) {
            $this->jsonResponse(['error' => 'Invalid date-time format. Use Y-m-d H:i:s or valid DateTime string.'], 400);
            return;
        }

        $game = new Game();
        $game->setUserId((int)$data['user_id'])
            ->setBoardSize((int)$data['board_size'])
            ->setMovesCount((int)$data['moves_count'])
            ->setMode($mode)
            ->setDurationSeconds((int)$data['duration_seconds'])
            ->setResult($result)
            ->setDateTime($voDate);

        try {
            $this->repo()->create($game);
            $this->jsonResponse(['success' => true], 201);
        } catch (\Throwable $e) {
            $this->jsonResponse(['error' => 'Failed to create game: ' . $e->getMessage()], 500);
        }
    }

    public function listAll(): void
    {
        $rows = $this->repo()->listAll();
        $payload = [];

        if ($rows) {
            foreach ($rows as $g) {
                $payload[] = $this->gameToArray($g);
            }
        }

        $this->jsonResponse($payload);
    }

    public function listByGameMode(): void
    {
        $gm = $_GET['game_mode'] ?? null;
        if (!$gm) {
            $this->jsonResponse(['error' => 'game_mode is required.'], 400);
            return;
        }

        try {
            $mode = GameMode::from($gm);
        } catch (\Throwable $e) {
            $this->jsonResponse(['error' => 'Invalid game_mode.'], 400);
            return;
        }

        $rows = $this->repo()->listByGameMode($mode);
        $payload = [];

        if ($rows) {
            foreach ($rows as $g) {
                $payload[] = $this->gameToArray($g);
            }
        }

        $this->jsonResponse($payload);
    }

    public function listByUser(): void
    {
        $userId = $_GET['user_id'] ?? null;
        if ($userId === null || $userId === '') {
            $this->jsonResponse(['error' => 'user_id is required.'], 400);
            return;
        }

        $gameModeParam = $_GET['game_mode'] ?? null;
        $mode = null;
        if ($gameModeParam !== null) {
            try {
                $mode = GameMode::from($gameModeParam);
            } catch (\Throwable $e) {
                $this->jsonResponse(['error' => 'Invalid game_mode.'], 400);
                return;
            }
        }

        $rows = $this->repo()->listByUser((int)$userId, $mode);
        $payload = [];

        if ($rows) {
            foreach ($rows as $g) {
                $payload[] = $this->gameToArray($g);
            }
        }

        $this->jsonResponse($payload);
    }

    public function findByID(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id === null || $id === '') {
            $this->jsonResponse(['error' => 'id is required.'], 400);
            return;
        }

        $game = $this->repo()->findByID((int)$id);
        if (!$game) {
            $this->jsonResponse(['error' => 'Game not found.'], 404);
            return;
        }

        $this->jsonResponse($this->gameToArray($game));
    }

    private function gameToArray(Game $g): array
    {
        return [
            'id' => $g->getId(),
            'user_id' => $g->getUserId(),
            'board_size' => $g->getBoardSize(),
            'moves_count' => $g->getMovesCount(),
            'mode' => $g->getMode()->value,
            'duration_seconds' => $g->getDurationSeconds(),
            'result' => $g->getResult()->value,
            'datetime' => (string)$g->getDateTime(),
        ];
    }
}
