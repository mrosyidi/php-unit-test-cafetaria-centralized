<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Repository\FoodRepositoryImpl;
    use Cafetaria\Entity\Food;

    class FoodRepositoryTest extends TestCase
    {
        private $pdoMock;
        private $foodRepository;

        protected function setUp(): void
        {
            $this->pdoMock = $this->createMock(\PDO::class);
            $this->foodRepository = new FoodRepositoryImpl($this->pdoMock);
        }

        public function testFindAllWithData()
        {
            $statementMock = $this->createMock(\PDOStatement::class);
            $statementMock->method('execute')->willReturn(true);
            $statementMock->method('fetchAll')->willReturn([
                ['name' => 'Nasi Goreng', 'price' => 15000],
                ['name' => 'Mie Goreng', 'price' => 12000],
            ]);

            $this->pdoMock->method('prepare')->willReturn($statementMock);
            $foods = $this->foodRepository->findAll();

            $this->assertCount(2, $foods);
            $this->assertInstanceOf(Food::class, $foods[0]);
            $this->assertEquals('Nasi Goreng', $foods[0]->getName());
            $this->assertEquals(15000, $foods[0]->getPrice());
        }

    }
