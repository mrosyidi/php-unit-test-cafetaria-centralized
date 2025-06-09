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
    }