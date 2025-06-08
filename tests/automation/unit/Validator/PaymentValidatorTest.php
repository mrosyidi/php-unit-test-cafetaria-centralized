<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Order;
    use Cafetaria\Validator\PaymentValidator;

    class PaymentValidatorTest extends TestCase
    {
        public function testIsCodeInItemsReturnsTrueWhenItemFound()
        {
            $items = [
                new Order(1, 50000),
                new Order(2, 22000),
                new Order(3, 35000)
            ];

            $this->assertTrue(PaymentValidator::isCodeInItems($items, 2));
        }
    }