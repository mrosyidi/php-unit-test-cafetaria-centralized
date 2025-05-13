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
    }