<?php 

    namespace Cafetaria;
    
    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Payment;
    use Cafetaria\Exception\InvalidPaymentException;

    class PaymentTest extends TestCase
    {
        public function testConstructorWithValidValues()
        {
            $payment = new Payment(1, 48000, 100000);
            $this->assertEquals(1, $payment->getCode());
            $this->assertEquals(48000, $payment->getTotal());
            $this->assertEquals(100000, $payment->getPay());
            $this->assertEquals(52000, $payment->getChange());
        }

        public function testConstructorWithNegativeTotal()
        {
            $this->expectException(InvalidPaymentException::class);
            $this->expectExceptionMessage("Total tidak boleh negatif.");

            new Payment(1, -48000, 100000);
        }

        public function testContructorWithNegativePay()
        {
            $this->expectException(InvalidPaymentException::class);
            $this->expectExceptionMessage("Jumlah bayar tidak boleh negatif.");

            new Payment(1, 48000, -100000);
        }

        public function testConstructWhenTotalGreaterThanPay()
        {
            $this->expectException(InvalidPaymentException::class);
            $this->expectExceptionMessage("Jumlah bayar kurang dari total.");

            new Payment(1, 68000, 50000);
        }

        public function testSetTotalUpdateChange()
        {
            $payment = new Payment(2, 48000, 100000);
            $payment->setTotal(64000);
            $this->assertEquals(64000, $payment->getTotal());
            $this->assertEquals(36000, $payment->getChange());
        }

        public function testSetTotalWithNegativeThrowsException()
        {
            $this->expectException(InvalidPaymentException::class);
            $this->expectExceptionMessage("Total tidak boleh negatif.");

            $payment = new Payment();
            $payment->setTotal(-52000);
        }

        public function testSetPayUpdateChange()
        {
            $payment = new Payment(3, 36000, 50000);
            $payment->setPay(100000);
            $this->assertEquals(100000, $payment->getPay());
            $this->assertEquals(64000, $payment->getChange());
        }

        public function testSetPayWithNegativeThrowsException()
        {
            $this->expectException(InvalidPaymentException::class);
            $this->expectExceptionMessage("Jumlah bayar tidak boleh negatif.");

            $payment = new Payment(2, 34000, 100000);
            $payment->setPay(-50000);
        }

        public function testSetPayLowerThanTotalThrowsException()
        {
            $this->expectException(InvalidPaymentException::class);
            $this->expectExceptionMessage("Jumlah bayar kurang dari total.");

            $payment = new Payment(2, 64000, 100000);
            $payment->setPay(50000);
        }
    }