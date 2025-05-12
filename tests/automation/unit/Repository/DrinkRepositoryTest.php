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
    }