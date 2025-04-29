<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Config\Database;

    class DatabaseTest extends TestCase 
    {
        public function testConnectionReturnsPDOInstance()
        {
            $pdo = Database::getConnection();
            $this->assertInstanceOf(\PDO::class, $pdo);
        }

        public function testConnectionIsWorking()
        {
            $pdo = Database::getConnection();
            $statement = $pdo->query("SELECT 1");
            $this->assertEquals(1, $statement->fetchColumn());
        }
    }