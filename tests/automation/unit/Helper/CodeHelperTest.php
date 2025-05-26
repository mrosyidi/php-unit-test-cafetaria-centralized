<?php 

    namespace Cafetaria\Helper;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Order;
    use Cafetaria\Entity\Payment;

    class CodeHelperTest extends TestCase
    {
        public function testCodeEmptyOrdersAndPayments()
        {
            $orders = [];
            $payments = [];

            $this->assertEquals(1, CodeHelper::code($orders, $payments, true));
        }

        public function testCodeEmptyOrdersWithPayments()
        {
            $orders = [];

            $payments = [
                new Payment(5), 
                new Payment(10)
            ];

            $this->assertEquals(11, CodeHelper::code($orders, $payments, true));
        }
    }