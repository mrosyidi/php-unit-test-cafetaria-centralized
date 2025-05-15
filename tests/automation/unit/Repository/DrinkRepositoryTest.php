<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Repository\DrinkRepositoryImpl;
    use Cafetaria\Entity\Drink;

    class DrinkRepositoryTest extends TestCase
    {
        private $pdo;
        private $statement;
        private $drinkRepository;

        protected function setUp(): void
        {
            $this->pdo = $this->createMock(\PDO::class);
            $this->statement = $this->createMock(\PDOStatement::class);
            $this->pdo->method('prepare')->willReturn($this->statement);
            $this->drinkRepository = new DrinkRepositoryImpl($this->pdo);
        }

        public function testFindAllWithData()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([
                ['name' => 'Jus Wortel', 'price' => 6000],
                ['name' => 'Es Oyen', 'price' => 12000],
            ]);

            $drinks = $this->drinkRepository->findAll();

            $this->assertCount(2, $drinks);
            $this->assertInstanceOf(Drink::class, $drinks[0]);
            $this->assertEquals('Jus Wortel', $drinks[0]->getName());
            $this->assertEquals(6000, $drinks[0]->getPrice());
        }

        public function testFindAllWithNoData()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([]);

            $drinks = $this->drinkRepository->findAll();

            $this->assertCount(0, $drinks);
        }

        public function testFindAllWithQueryFailure()
        {
            $this->statement->method('execute')->willReturn(false);

            $drinks = $this->drinkRepository->findAll();

            $this->assertCount(0, $drinks);
        }

        public function testFindAllWithNullName()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([
                ['name' => null, 'price' => 12000]
            ]);

            $drinks = $this->drinkRepository->findAll();

            $this->assertNull($drinks[0]->getName());
        }

        public function testFindAllWithEmptyString()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([
                ['name' => '', 'price' => 12000]
            ]);

            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Nama tidak boleh kosong.");

            $this->drinkRepository->findAll();
        }

        public function testFindAllWithNullPrice()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([
                ['name' => 'Es Campur', 'price' => null]
            ]);

            $drinks = $this->drinkRepository->findAll();
            
            $this->assertNull($drinks[0]->getPrice());
        }

        public function testFindAllWithZeroPrice()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([
                ['name' => 'Jus Melon', 'price' => 0]
            ]);

            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Harga harus lebih dari nol.");

            $this->drinkRepository->findAll();
        }

        public function testFindAllWithNegativePrice()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([
                ['name' => 'Jus Jambu', 'price' => -12000]
            ]);

            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Harga harus lebih dari nol.");

            $this->drinkRepository->findAll();
        }

        public function testSaveSuccess()
        {
            $this->statement->expects($this->once())
            ->method('execute')->with(["Es Campur", 10000]);

            $this->pdo->expects($this->once())
            ->method('prepare')->with("INSERT INTO drinks(name,price) VALUES(?,?)")
            ->willReturn($this->statement);

            $this->drinkRepository->save(new Drink("Es Campur", 10000));
        }

        public function testSavePrepareFails()
        {
            $this->expectException(\PDOException::class);

            $this->pdo->expects($this->once())
            ->method('prepare')
            ->will($this->throwException(new \PDOException("Database error")));

            $this->drinkRepository->save(new Drink("Jus Wortel", 6000));
        }

        public function testSaveExecuteFails()
        {
            $this->expectException(\PDOException::class);

            $this->pdo->expects($this->once())
            ->method('prepare')->with("INSERT INTO drinks(name,price) VALUES(?,?)")
            ->willReturn($this->statement);

            $this->statement->expects($this->once())
            ->method('execute')
            ->will($this->throwException(new \PDOException("Execution failed")));

            $this->drinkRepository->save(new Drink("Es Teh", 4000));
        }

        public function testRemoveReturnsTrueWhenRowDeleted()
        {
            $this->statement->expects($this->once())
            ->method('execute')->with(['Es Campur']);

            $this->statement->expects($this->once())
            ->method('rowCount')->willReturn(1);

            $this->pdo->expects($this->once())
            ->method('prepare')->with("DELETE FROM drinks WHERE name=?")
            ->willReturn($this->statement);

            $result = $this->drinkRepository->remove('Es Campur');

            $this->assertTrue($result);
        }

        public function testRemoveReturnsFalseWhenNoRowDeleted()
        {
            $this->statement->expects($this->once())
            ->method('execute')->with(['Es Oyen']);

            $this->statement->expects($this->once())
            ->method('rowCount')->willReturn(0);

            $this->pdo->expects($this->once())
            ->method('prepare')->with("DELETE FROM drinks WHERE name=?")
            ->willReturn($this->statement);

            $result = $this->drinkRepository->remove('Es Oyen');

            $this->assertFalse($result);
        }
    }