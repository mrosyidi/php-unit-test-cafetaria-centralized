<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Detail;
    use Cafetaria\Repository\DetailRepositoryImpl;
    use Cafetaria\Exception\InvalidDetailException;


    class DetailRepositoryTest extends TestCase
    {
        private $pdo;
        private $statement;
        private $detailRepository;

        protected function setUp(): void
        {
            $this->pdo = $this->createMock(\PDO::class);
            $this->statement = $this->createMock(\PDOStatement::class);
            $this->pdo->method('prepare')->willReturn($this->statement);
            $this->detailRepository = new DetailRepositoryImpl($this->pdo);
        }

        public function testFindAllWithData()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([
                ['code' => 1, 'name' => 'Nasi Goreng', 'price' => 10000, 'qty' => 2],
                ['code' => 1, 'name' => 'Es Oyen', 'price' => 12000, 'qty' => 2],
                ['code' => 2, 'name' => 'Es Campur', 'price' => 12000, 'qty' => 1],
            ]);

            $details = $this->detailRepository->findAll();

            $this->assertCount(3, $details);
            $this->assertInstanceOf(Detail::class, $details[0]);
            $this->assertEquals(1, $details[0]->getCode());
            $this->assertEquals('Nasi Goreng', $details[0]->getName());
            $this->assertEquals(10000, $details[0]->getPrice());
            $this->assertEquals(2, $details[0]->getQty());
            $this->assertEquals(20000, $details[0]->getSubTotal());
        }
    }