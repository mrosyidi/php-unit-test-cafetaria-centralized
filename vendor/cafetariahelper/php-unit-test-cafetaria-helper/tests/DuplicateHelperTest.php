<?php   

    namespace Cafetaria\Helper;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Helper\Entity\Order;

    class DuplicateHelperTest extends TestCase
    {   
        public function testDuplicateReturnsCorrectElements()
        {
            $orders = [
                new Order(1, 24000),
                new Order(1, 30000),
                new Order(2, 12000)
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
                new Order(1, 12000),
                new Order(2, 15000),
                new Order(3, 24000)
            ];

            $result = DuplicateHelper::duplicate($orders, 4);

            $this->assertEmpty($result);
        }

        public function testDuplicateThrowsExceptionIfItemNotObject()
        {
            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('Semua item order harus berupa objek.');

            $orders = ["Bukan objek"];

            DuplicateHelper::duplicate($orders, 1);
        }

        public function testDuplicateThrowsExceptionIfMissingGetCodeMethod()
        {
            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('Setiap objek order harus memiliki metode getCode().');

            $orders = [new \stdClass()];

            DuplicateHelper::duplicate($orders, 1);
        }
    }