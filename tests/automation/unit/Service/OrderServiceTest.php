<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Order;
    use Cafetaria\Repository\OrderRepository;
    use Cafetaria\Service\OrderServiceImpl;
    use Cafetaria\Exception\InvalidOrderException;

    class OrderServiceTest extends TestCase
    {
        private $orderRepository;
        private $orderService;

        protected function setUp(): void
        {
            $this->orderRepository = $this->createMock(OrderRepository::class);
            $this->orderService = new OrderServiceImpl($this->orderRepository);
        }

        public function testGetAllOrderWhenDataExist()
        {
            $orders = [
                $this->createConfiguredMock(Order::class, [
                    'getCode' => 1,
                    'getName' => 'Ayam Goreng',
                    'getPrice' => 12000,
                    'getQty' => 1,
                    'getSubTotal' => 12000
                ]),
                $this->createConfiguredMock(Order::class, [
                    'getCode' => 1,
                    'getName' => 'Es Oyen',
                    'getPrice' => 12000,
                    'getQty' => 1,
                    'getSubTotal' => 12000
                ])
            ];

            $this->orderRepository->method('findAll')->willReturn($orders);

            $orders = $this->orderService->getAllOrder();

            $this->assertCount(2, $orders);
            $this->assertEquals(1, $orders[0]->getCode());
            $this->assertEquals('Ayam Goreng', $orders[0]->getName());
            $this->assertEquals(12000, $orders[0]->getPrice());
            $this->assertEquals(1, $orders[0]->getQty());
            $this->assertEquals(12000, $orders[0]->getSubTotal());
        }

        public function testGetAllOrderWhenNoDataExist()
        {
            $this->orderRepository->method('findAll')->willReturn([]);

            $orders = $this->orderService->getAllOrder();

            $this->assertCount(0, $orders);
        }

        public function testAddOrderSuccess()
        {
            $this->orderRepository->expects($this->once())
            ->method('save')->with(new Order(1, "Ayam Panggang", 12000, 2, 24000));

            $this->orderService->addOrder(1, "Ayam Panggang", 12000, 2, 24000);
        }

        public function testAddOrderWithNegativePriceThrowsException()
        {
            $this->expectException(InvalidOrderException::class);
            $this->orderService->addOrder(1, "Mie Ayam", -12000, 2, -24000);
        }

        public function testAddOrderWithZeroQtyThrowsException()
        {
            $this->expectException(InvalidOrderException::class);
            $this->orderService->addOrder(1, "Rawon", 12000, 0, 0);
        }

        public function testAddOrderWithNegativeQtyThrowsException()
        {
            $this->expectException(InvalidOrderException::class);
            $this->orderService->addOrder(1, "Es Oyen", 12000, -3, -36000);
        }

        public function testAddOrderSaveFailsThrowsException()
        {
            $this->orderRepository->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \PDOException("Database error")));

            $this->expectException(\PDOException::class);

            $this->orderService->addOrder(1, "Es Campur", 12000, 1, 12000);
        }
    }