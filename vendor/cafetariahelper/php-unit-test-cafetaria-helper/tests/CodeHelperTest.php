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

        public function testCodeEmptyOrdersWithPayments()
        {
            $orders = [];

            $payments = [
                new Payment(5), 
                new Payment(10)
            ];

            $this->assertEquals(11, CodeHelper::code($orders, $payments, true));
        }

        public function testCodeOrdersWithoutExit()
        {
            $orders = [
                new Order(2, 2), 
                new Order(4, 2)
            ];

            $payments = [];

            $this->assertEquals(4, CodeHelper::code($orders, $payments, false));
        }

        public function testCodeOrdersWithExitNoPayments()
        {
            $orders = [
                new Order(3, 2), 
                new Order(6, 3)
            ];

            $payments = [];

            $this->assertEquals(7, CodeHelper::code($orders, $payments, true));
        }

        public function testCodeOrdersWithExitAndPayments()
        {
            $orders = [
                new Order(5, 2), 
                new Order(3, 2)
            ];

            $payments = [
                new Payment(10)
            ];

            $this->assertEquals(11, CodeHelper::code($orders, $payments, true));
        }

        public function testCodeThrowsExceptionInvalidOrder()
        {
            $orders = [
                new \stdClass()
            ];

            $payments = [];

            $this->expectException(\Exception::class);
            CodeHelper::code($orders, $payments, true);
        }

        public function testCodeThrowsExceptionInvalidPayment()
        {
            $orders = [];

            $payments = [
                new \stdClass()
            ];

            $this->expectException(\Exception::class);
            CodeHelper::code($orders, $payments, true);
        }
    }