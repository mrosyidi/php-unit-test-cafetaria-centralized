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
    }