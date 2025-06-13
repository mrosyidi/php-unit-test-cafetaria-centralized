<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Config\Database;
    use Cafetaria\Entity\Payment;
    use Cafetaria\Repository\PaymentRepositoryImpl;
    use Cafetaria\Service\PaymentServiceImpl;
    use Cafetaria\Exception\InvalidPaymentException;

    class PaymentServiceIntegrationTest extends TestCase 
    {
        private $paymentRepository;
        private $paymentService;

        public function setUp(): void 
        {
            $connection = Database::getConnection();
            $this->paymentRepository = new PaymentRepositoryImpl($connection);
            $this->paymentService = new PaymentServiceImpl($this->paymentRepository);
            $this->paymentRepository->removeAll();
        }

        public function testGetAllPaymentWithData()
        {
            $payment = new Payment(1, 26000, 50000, 24000);
            
            $this->paymentRepository->save($payment);

            $payments = $this->paymentService->getAllpayment();

            $this->assertCount(1, $payments);
            $this->assertEquals($payment->getCode(), $payments[0]->getCode());
            $this->assertEquals($payment->getTotal(), $payments[0]->getTotal());
            $this->assertEquals($payment->getPay(), $payments[0]->getPay());
            $this->assertEquals($payment->getChange(), $payments[0]->getChange());
        }

        public function testGetAllPaymentWithNoData()
        {
            $payments = $this->paymentService->getAllPayment();

            $this->assertEmpty($payments);
        }

        public function testAddPaymentSuccess()
        {
            $this->paymentService->addPayment(1, 32000, 50000, 18000);

            $payments = $this->paymentService->getAllPayment();

            $this->assertCount(1, $payments);
            $this->assertEquals(1, $payments[0]->getCode());
            $this->assertEquals(32000, $payments[0]->getTotal());
            $this->assertEquals(50000, $payments[0]->getPay());
            $this->assertEquals(18000, $payments[0]->getChange());
        }
    }