<?php

namespace App\Controllers;

class HealthController
{

    private function jsonResponse($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    public function health(): void
    {
        $this->jsonResponse(['status' => 'ok'], 200);
    }
}
