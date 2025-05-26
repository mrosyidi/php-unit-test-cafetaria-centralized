<?php 

    namespace Cafetaria\Helper;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Helper\Entity\Order;
    use Cafetaria\Helper\Entity\Payment;

    class CodeHelperTest extends TestCase
    {
        public function testCodeEmptyOrdersAndPayments()
        {
            $orders = [];
            $payments = [];

            $this->assertEquals(1, CodeHelper::code($orders, $payments, true));
        }
    }