<?php 

    namespace Cafetaria\Helper;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Order;
    use Cafetaria\Helper\PayHelper;

    class PayHelperTest extends TestCase
    {
        public function testPayReturnsCorrectTotal()
        {
            $orders = [
                new Order(1, "Rawon", 12000, 2),
                new Order(1, "Es Oyen", 15000, 2),
                new Order(2, "Es Campur", 10000, 2)
            ];

            $total = PayHelper::pay($orders, 1);
            $this->assertEquals(54000, $total);
        }

        public function testPayReturnsZeroWhenNoMatchingCode()
        {
            $orders = [
                new Order(1, "Rawon", 12000, 2),
                new Order(1, "Es Oyen", 15000, 2),
                new Order(2, "Es Campur", 10000, 2)
            ];

            $total = PayHelper::pay($orders, 4);
            $this->assertEquals(0, $total);
        }

        public function testPayThrowsExceptionIfItemNotObject()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Semua item order harus berupa objek.');

            $orders = ["Bukan objek"];

            PayHelper::pay($orders, 2);
        }

        public function testPayThrowsExceptionIfMissingMethod()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Setiap objek order harus memiliki metode getCode() dan getSubTotal().');

            $orders = [new \stdClass()];

            PayHelper::pay($orders, 1001);
        }
    }