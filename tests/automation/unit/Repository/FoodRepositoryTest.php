<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Food;
    use Cafetaria\Repository\FoodRepositoryImpl;
    use Cafetaria\Exception\InvalidFoodException;


    class FoodRepositoryTest extends TestCase
    {
        private $pdo;
        private $statement;
        private $foodRepository;

        protected function setUp(): void
        {
            $this->pdo = $this->createMock(\PDO::class);
            $this->statement = $this->createMock(\PDOStatement::class);
            $this->pdo->method('prepare')->willReturn($this->statement);
            $this->foodRepository = new FoodRepositoryImpl($this->pdo);
        }

        public function testFindAllWithData()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([
                ['name' => 'Nasi Goreng', 'price' => 12000],
                ['name' => 'Mie Goreng', 'price' => 7000],
            ]);

            $foods = $this->foodRepository->findAll();

            $this->assertCount(2, $foods);
            $this->assertInstanceOf(Food::class, $foods[0]);
            $this->assertEquals('Nasi Goreng', $foods[0]->getName());
            $this->assertEquals(12000, $foods[0]->getPrice());
        }

        public function testFindAllWithNoData()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([]);

            $foods = $this->foodRepository->findAll();

            $this->assertCount(0, $foods);
        }

        public function testFindAllWithQueryFailure()
        {
            $this->statement->method('execute')->willReturn(false);

            $foods = $this->foodRepository->findAll();

            $this->assertCount(0, $foods);

        }

        public function testFindAllWithNullName()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([
                ['name' => null, 'price' => 12000]
            ]);

            $foods = $this->foodRepository->findAll();

            $this->assertNull($foods[0]->getName());
        }

        public function testFindAllWithEmptyString()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([
                ['name' => '', 'price' => 12000]
            ]);

            $this->expectException(InvalidFoodException::class);
            $this->expectExceptionMessage("Nama tidak boleh kosong.");

            $this->foodRepository->findAll();
        }

        public function testFindAllWithNullPrice()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([
                ['name' => 'Nasi Goreng', 'price' => null]
            ]);

            $foods = $this->foodRepository->findAll();
            
            $this->assertNull($foods[0]->getPrice());
        }

        public function testFindAllWithZeroPrice()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([
                ['name' => 'Nasi Goreng', 'price' => 0]
            ]);

            $this->expectException(InvalidFoodException::class);
            $this->expectExceptionMessage("Harga harus lebih dari nol.");

            $this->foodRepository->findAll();
        }

        public function testFindAllWithNegativePrice()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([
                ['name' => 'Nasi Goreng', 'price' => -12000]
            ]);

            $this->expectException(InvalidFoodException::class);
            $this->expectExceptionMessage("Harga harus lebih dari nol.");

            $this->foodRepository->findAll();
        }

        public function testSaveSuccess()
        {
            $this->statement->expects($this->once())
            ->method('execute')->with(["Soto Ayam", 10000]);

            $this->pdo->expects($this->once())
            ->method('prepare')->with("INSERT INTO foods(name,price) VALUES(?,?)")
            ->willReturn($this->statement);

            $this->foodRepository->save(new Food("Soto Ayam", 10000));
        }

        public function testSavePrepareFails()
        {
            $this->expectException(\PDOException::class);

            $this->pdo->expects($this->once())
            ->method('prepare')
            ->will($this->throwException(new \PDOException("Database error")));

            $this->foodRepository->save(new Food("Rawon", 12000));
        }

        public function testSaveExecuteFails()
        {
            $this->expectException(\PDOException::class);

            $this->pdo->expects($this->once())
            ->method('prepare')->with("INSERT INTO foods(name,price) VALUES(?,?)")
            ->willReturn($this->statement);

            $this->statement->expects($this->once())
            ->method('execute')
            ->will($this->throwException(new \PDOException("Execution failed")));

            $this->foodRepository->save(new Food("Rawon", 12000));
        }

        public function testRemoveReturnsTrueWhenRowDeleted()
        {
            $this->statement->expects($this->once())
            ->method('execute')->with(['Rawon']);

            $this->statement->expects($this->once())
            ->method('rowCount')->willReturn(1);

            $this->pdo->expects($this->once())
            ->method('prepare')->with("DELETE FROM foods WHERE name=?")
            ->willReturn($this->statement);

            $result = $this->foodRepository->remove('Rawon');

            $this->assertTrue($result);
        }

        public function testRemoveReturnsFalseWhenNoRowDeleted()
        {
            $this->statement->expects($this->once())
            ->method('execute')->with(['Soto Daging']);

            $this->statement->expects($this->once())
            ->method('rowCount')->willReturn(0);

            $this->pdo->expects($this->once())
            ->method('prepare')->with("DELETE FROM foods WHERE name=?")
            ->willReturn($this->statement);

            $result = $this->foodRepository->remove('Soto Daging');

            $this->assertFalse($result);
        }

        public function testRemoveThrowsExceptionOnPrepareFailure()
        {
            $this->expectException(\PDOException::class);

            $this->pdo->method('prepare')
            ->willThrowException(new \PDOException("Database error"));

            $this->foodRepository->remove('Gado-Gado');
        }

        public function testRemoveThrowsExceptionOnExecuteFailure()
        {
            $this->statement->method('execute')
            ->willThrowException(new \PDOException("Execution failed"));

            $this->pdo->method('prepare')->willReturn($this->statement);

            $this->expectException(\PDOException::class);

            $this->foodRepository->remove("Mie Goreng");
        }

        public function testRemoveReturnsTrueWhenMultipleRowDeleted()
        {
            $this->statement->expects($this->once())
            ->method('execute')->with(['Ayam Panggang']);

            $this->statement->expects($this->once())
            ->method('rowCount')->willReturn(2);

            $this->pdo->expects($this->once())
            ->method('prepare')->with("DELETE FROM foods WHERE name=?")
            ->willReturn($this->statement);

            $result = $this->foodRepository->remove('Ayam Panggang');

            $this->assertTrue($result);
        }

        public function testRemoveAll()
        {
            $this->pdo->expects($this->once())
            ->method('prepare')->with("DELETE FROM foods")
            ->willReturn($this->statement);

            $this->foodRepository->removeAll();
        }
    }
