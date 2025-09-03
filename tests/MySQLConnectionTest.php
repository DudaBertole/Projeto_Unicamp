<?php

namespace Tests\Repositories\Repositories_MySQL;

use PHPUnit\Framework\TestCase;
use App\Repositories\Repositories_MySQL\MySQLConnection;
use PDO;

class MySQLConnectionTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $dotenvPath = dirname(__DIR__, 1);
        if (file_exists($dotenvPath . '/.env')) {
            $dotenv = \Dotenv\Dotenv::createImmutable($dotenvPath);
            $dotenv->load();
        }
    }

    public function testGetConnectionReturnsPDOInstance()
    {
        $connection = MySQLConnection::getInstance()->getConnection();
        $this->assertInstanceOf(PDO::class, $connection);
    }

    public function testConnectionIsSingleton()
    {
        $conn1 = MySQLConnection::getInstance()->getConnection();
        $conn2 = MySQLConnection::getInstance()->getConnection();
        $this->assertSame($conn1, $conn2);
    }
}