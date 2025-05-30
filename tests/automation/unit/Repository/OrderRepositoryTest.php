<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Order;
    use Cafetaria\Repository\OrderRepositoryImpl;
    use Cafetaria\Exception\InvalidOrderException;


    class OrderRepositoryTest extends TestCase
    {
        private $pdo;
        private $statement;
        private $orderRepository;

        protected function setUp(): void
        {
            $this->pdo = $this->createMock(\PDO::class);
            $this->statement = $this->createMock(\PDOStatement::class);
            $this->pdo->method('prepare')->willReturn($this->statement);
            $this->orderRepository = new OrderRepositoryImpl($this->pdo);
        }

        public function testFindAllWithData()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([
                ['code' => 1, 'name' => 'Nasi Goreng', 'price' => 10000, 'qty' => 2],
                ['code' => 1, 'name' => 'Es Oyen', 'price' => 12000, 'qty' => 2],
                ['code' => 2, 'name' => 'Es Campur', 'price' => 12000, 'qty' => 1],
            ]);

            $orders = $this->orderRepository->findAll();

            $this->assertCount(3, $orders);
            $this->assertInstanceOf(Order::class, $orders[0]);
            $this->assertEquals(1, $orders[0]->getCode());
            $this->assertEquals('Nasi Goreng', $orders[0]->getName());
            $this->assertEquals(10000, $orders[0]->getPrice());
            $this->assertEquals(2, $orders[0]->getQty());
            $this->assertEquals(20000, $orders[0]->getSubTotal());
        }

        public function testFindAllWithNoData()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([]);

            $orders = $this->orderRepository->findAll();

            $this->assertCount(0, $orders);
        }

        public function testFindAllWithQueryFailure()
        {
            $this->statement->method('execute')->willReturn(false);

            $orders = $this->orderRepository->findAll();

            $this->assertCount(0, $orders);
        }

        public function testSaveSuccess()
        {
            $this->statement->expects($this->once())
            ->method('execute')->with([1, "Soto Ayam", 10000, 2, 20000]);

            $this->pdo->expects($this->once())
            ->method('prepare')
            ->with("INSERT INTO orders(code,name,price,qty,sub_total) VALUES(?,?,?,?,?)")
            ->willReturn($this->statement);

            $this->orderRepository->save(new Order(1, "Soto Ayam", 10000, 2, 20000));
        }

        public function testSavePrepareFails()
        {
            $this->expectException(\PDOException::class);

            $this->pdo->expects($this->once())
            ->method('prepare')
            ->will($this->throwException(new \PDOException("Database error")));

            $this->orderRepository->save(new Order(1, "Rawon", 12000, 1, 12000));
        }

        public function testSaveExecuteFails()
        {
            $this->expectException(\PDOException::class);

            $this->pdo->expects($this->once())
            ->method('prepare')
            ->with("INSERT INTO orders(code,name,price,qty,sub_total) VALUES(?,?,?,?,?)")
            ->willReturn($this->statement);

            $this->statement->expects($this->once())
            ->method('execute')
            ->will($this->throwException(new \PDOException("Execution failed")));

            $this->orderRepository->save(new Order(1, "Soto Ayam", 10000, 2, 20000));
        }

        public function testRemoveSuccess()
        {
            $this->statement->expects($this->once())
            ->method('execute')->with([1]);

            $this->pdo->expects($this->once())
            ->method('prepare')->with("DELETE FROM orders WHERE code=?")
            ->willReturn($this->statement);

            $this->orderRepository->remove(1);
        }

        public function testRemoveThrowsExceptionOnPrepareFailure()
        {
            $this->expectException(\PDOException::class);

            $this->pdo->method('prepare')
            ->willThrowException(new \PDOException("Database error"));

            $this->orderRepository->remove(1);
        }

        public function testRemoveThrowsExceptionOnExecuteFailure()
        {
            $this->statement->method('execute')
            ->willThrowException(new \PDOException("Execution failed"));

            $this->pdo->method('prepare')->willReturn($this->statement);

            $this->expectException(\PDOException::class);

            $this->orderRepository->remove(1);
        }

        public function testRemoveAll()
        {
            $this->pdo->expects($this->once())
            ->method('prepare')->with("DELETE FROM orders")
            ->willReturn($this->statement);

            $this->orderRepository->removeAll();
        }
    }