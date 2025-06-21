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

        public function testFindAllWithNoData()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([]);

            $details = $this->detailRepository->findAll();

            $this->assertCount(0, $details);
        }

        public function testFindAllWithQueryFailure()
        {
            $this->statement->method('execute')->willReturn(false);

            $details = $this->detailRepository->findAll();

            $this->assertCount(0, $details);
        }

        public function testSaveSuccess()
        {
            $this->statement->expects($this->once())
            ->method('execute')->with([1, "Soto Ayam", 10000, 2, 20000]);

            $this->pdo->expects($this->once())
            ->method('prepare')
            ->with("INSERT INTO details(code,name,price,qty,sub_total) VALUES(?,?,?,?,?)")
            ->willReturn($this->statement);

            $this->detailRepository->save(new Detail(1, "Soto Ayam", 10000, 2, 20000));
        }

        public function testSavePrepareFails()
        {
            $this->expectException(\PDOException::class);

            $this->pdo->expects($this->once())
            ->method('prepare')
            ->will($this->throwException(new \PDOException("Database error")));

            $this->detailRepository->save(new Detail(1, "Rawon", 12000, 1, 12000));
        }
    }