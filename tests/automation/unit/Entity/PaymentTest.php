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
    }