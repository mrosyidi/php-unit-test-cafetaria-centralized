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

        public function testDuplicateReturnsEmptyArrayIfNoMatch()
        {
            $orders = [
                new Order(1, "Mie Ayam", 6000, 2),
                new Order(2, "Es Oyen", 15000, 2),
                new Order(3, "Rawon", 12000, 2)
            ];

            $result = DuplicateHelper::duplicate($orders, 4);

            $this->assertEmpty($result);
        }

        public function testDuplicateThrowsExceptionIfItemNotObject()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Semua item order harus berupa objek.');

            $orders = ["Bukan objek"];

            DuplicateHelper::duplicate($orders, 1);
        }

        public function testDuplicateThrowsExceptionIfMissingGetCodeMethod()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Setiap objek order harus memiliki metode getCode().');

            $orders = [new \stdClass()];

            DuplicateHelper::duplicate($orders, 1);
        }
    }