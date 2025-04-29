<?php 

    namespace Cafetaria\Helper;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Helper\Entity\Order;

    class PayHelperTest extends TestCase
    {
        public function testPayReturnsCorrectTotal()
        {
            $orders = [
                new Order(1, 24000),
                new Order(1, 30000),
                new Order(2, 20000)
            ];

            $total = PayHelper::pay($orders, 1);
            $this->assertEquals(54000, $total);
        }

        public function testPayReturnsZeroWhenNoMatchingCode()
        {
            $orders = [
                new Order(1, 30000),
                new Order(2, 24000),
                new Order(3, 20000)
            ];

            $total = PayHelper::pay($orders, 4);
            $this->assertEquals(0, $total);
        }

        public function testPayThrowsExceptionIfItemNotObject()
        {
            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('Semua item order harus berupa objek.');

            $orders = ["Bukan objek"];

            PayHelper::pay($orders, 2);
        }

        public function testPayThrowsExceptionIfMissingMethod()
        {
            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('Setiap objek order harus memiliki metode getCode() dan getSubTotal().');

            $orders = [new \stdClass()];

            PayHelper::pay($orders, 1001);
        }
    }