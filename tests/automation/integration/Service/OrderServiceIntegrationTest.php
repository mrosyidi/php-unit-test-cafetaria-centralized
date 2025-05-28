<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Config\Database;
    use Cafetaria\Entity\Order;
    use Cafetaria\Repository\OrderRepositoryImpl;
    use Cafetaria\Service\OrderServiceImpl;
    use Cafetaria\Exception\InvalidOrderException;

    class OrderServiceIntegrationTest extends TestCase 
    {
        private $orderRepository;
        private $orderService;

        public function setUp(): void 
        {
            $connection = Database::getConnection();
            $this->orderRepository = new OrderRepositoryImpl($connection);
            $this->orderService = new OrderServiceImpl($this->orderRepository);
            $this->orderRepository->removeAll();
        }

        public function testGetAllOrderWithData()
        {
            $order = new Order(1, "Jus Melon", 7000, 2);
            
            $this->orderRepository->save($order);

            $orders = $this->orderService->getAllOrder();

            $this->assertCount(1, $orders);
            $this->assertEquals($order->getCode(), $orders[0]->getCode());
            $this->assertEquals($order->getName(), $orders[0]->getName());
            $this->assertEquals($order->getPrice(), $orders[0]->getPrice());
            $this->assertEquals($order->getQty(), $orders[0]->getQty());
            $this->assertEquals(14000, $orders[0]->getSubTotal());
        }

        public function testGetAllFoodWithNoData()
        {
            $orders = $this->orderService->getAllOrder();

            $this->assertEmpty($orders);
        }
    }