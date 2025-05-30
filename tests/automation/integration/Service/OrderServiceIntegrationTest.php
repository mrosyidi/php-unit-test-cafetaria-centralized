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

        public function testAddOrderSuccess()
        {
            $this->orderService->addOrder(1, "Soto Daging", 12000, 1);

            $orders = $this->orderService->getAllOrder();

            $this->assertCount(1, $orders);
            $this->assertEquals(1, $orders[0]->getCode());
            $this->assertEquals("Soto Daging", $orders[0]->getName());
            $this->assertEquals(12000, $orders[0]->getPrice());
            $this->assertEquals(1, $orders[0]->getQty());
            $this->assertEquals(12000, $orders[0]->getSubTotal());
        }

        public function testAddOrderWithNegativePriceThrowsException()
        {
            $this->expectException(InvalidOrderException::class);
            $this->expectExceptionMessage("Harga tidak boleh negatif.");

            $this->orderService->addOrder(1, "Gado-Gado", -12000, 2); 
        }

        public function testAddOrderWithZeroQtyThrowsException()
        {
            $this->expectException(InvalidOrderException::class);
            $this->expectExceptionMessage("Kuantitas harus lebih dari nol.");

            $this->orderService->addOrder(2, "Rawon", 12000, 0); 
        }

        public function testAddOrderWithNegativeQtyThrowsException()
        {
            $this->expectException(InvalidOrderException::class);
            $this->expectExceptionMessage("Kuantitas harus lebih dari nol.");

            $this->orderService->addOrder(2, "Ayam Goreng", 12000, -2); 
        }

        public function testRemoveOrderSuccess()
        {
            $this->orderService->addOrder(1, "Pastel", 5000, 2);
            
            $orders = $this->orderService->getAllOrder();
            
            $this->assertCount(1, $orders);

            $this->orderService->removeOrder(1);

            $orders = $this->orderService->getAllOrder();

            $this->assertCount(0, $orders);
        }
    }