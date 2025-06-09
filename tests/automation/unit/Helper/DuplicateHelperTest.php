<?php   

    namespace Cafetaria\Helper;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Order;
    use Cafetaria\Helper\DuplicateHelper;

    class DuplicateHelperTest extends TestCase
    {   
        public function testDuplicateReturnsCorrectElements()
        {
            $orders = [
                new Order(1, "Rawon", 12000, 2),
                new Order(1, "Es Oyen", 15000, 2),
                new Order(2, "Es Campur", 10000, 2)
            ];

            $result = DuplicateHelper::duplicate($orders, 1);

            $this->assertCount(2, $result);

            foreach ($result as $order) 
            {
                $this->assertEquals(1, $order->getCode());
            }
        }
    }