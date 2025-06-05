<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Payment;
    use Cafetaria\Repository\PaymentRepositoryImpl;
    use Cafetaria\Exception\InvalidPaymentException;


    class PaymentRepositoryTest extends TestCase
    {
        private $pdo;
        private $statement;
        private $paymentRepository;

        protected function setUp(): void
        {
            $this->pdo = $this->createMock(\PDO::class);
            $this->statement = $this->createMock(\PDOStatement::class);
            $this->pdo->method('prepare')->willReturn($this->statement);
            $this->paymentRepository = new PaymentRepositoryImpl($this->pdo);
        }

        public function testFindAllWithData()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([
                ['code' => 1, 'total' => 48000, 'pay' => 100000]
            ]);

            $payments = $this->paymentRepository->findAll();

            $this->assertCount(1, $payments);
            $this->assertInstanceOf(Payment::class, $payments[0]);
            $this->assertEquals(1, $payments[0]->getCode());
            $this->assertEquals(48000, $payments[0]->getTotal());
            $this->assertEquals(100000, $payments[0]->getPay());
            $this->assertEquals(52000, $payments[0]->getChange());
        }

        public function testFindAllWithNoData()
        {
            $this->statement->method('execute')->willReturn(true);
            $this->statement->method('fetchAll')->willReturn([]);

            $payments = $this->paymentRepository->findAll();

            $this->assertCount(0, $payments);
        }

        public function testFindAllWithQueryFailure()
        {
            $this->statement->method('execute')->willReturn(false);

            $payments = $this->paymentRepository->findAll();

            $this->assertCount(0, $payments);
        }
    }