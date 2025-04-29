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
    }