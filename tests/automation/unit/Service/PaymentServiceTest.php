<?php 

    namespace Cafetaria\Service;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Payment;
    use Cafetaria\Repository\PaymentRepository;
    use Cafetaria\Service\PaymentServiceImpl;
    use Cafetaria\Exception\InvalidPaymentException;

    class PaymentServiceTest extends TestCase 
    {
        private $paymentRepository;
        private $paymentService;

        public function setUp(): void 
        {
            $this->paymentRepository = $this->createMock(PaymentRepository::class);
            $this->paymentService = new PaymentServiceImpl($this->paymentRepository);
        }

        public function testGetAllPaymentWhenDataExist()
        {
            $payments = [
                $this->createConfiguredMock(Payment::class, [
                    'getCode' => 1,
                    'getTotal' => 68000,
                    'getPay' => 100000,
                    'getChange' => 32000
                ]),
                $this->createConfiguredMock(Payment::class, [
                    'getCode' => 2,
                    'getTotal' => 52000,
                    'getPay' => 100000,
                    'getChange' => 48000
                ])
            ];

            $this->paymentRepository->method('findAll')->willReturn($payments);

            $payments = $this->paymentService->getAllPayment();

            $this->assertCount(2, $payments);
            $this->assertEquals(1, $payments[0]->getCode());
            $this->assertEquals(68000, $payments[0]->getTotal());
            $this->assertEquals(100000, $payments[0]->getPay());
            $this->assertEquals(32000, $payments[0]->getChange());
        }

        public function testGetAllPaymentWhenNoDataExist()
        {
            $this->paymentRepository->method('findAll')->willReturn([]);

            $payments = $this->paymentService->getAllPayment();

            $this->assertCount(0, $payments);
        }

        public function testAddPaymentSuccess()
        {
            $this->paymentRepository->expects($this->once())
            ->method('save')->with(new Payment(1, 68000, 100000, 32000));

            $this->paymentService->addPayment(1, 68000, 100000, 32000);
        }
    }