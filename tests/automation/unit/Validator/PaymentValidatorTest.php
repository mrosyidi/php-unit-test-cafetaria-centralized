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

            $result = PaymentValidator::isCodeInItems($items, 2);

            $this->assertTrue($result);
        }

        public function testIsCodeInItemsReturnsFalseWhenItemNotFound()
        {
            $items = [
                new Order(1, 50000),
                new Order(2, 22000),
                new Order(3, 35000)
            ];

            $result = PaymentValidator::isCodeInItems($items, 4);

            $this->assertFalse($result);
        }

        public function testIsCodeInItemsThrowsExceptionEmptyItemsArray()
        {
            $items = [];

            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Item tidak boleh kosong.');
            
            PaymentValidator::isCodeInItems($items, 1);
        }

        public function testIsCodeInItemsThrowsExceptionItemNotObject()
        {
            $items = ["Bukan objek"];

            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Item pada index 0 bukan objek.');

            PaymentValidator::isCodeInItems($items, 1);
        }

        public function testIsCodeInItemsThrowsExceptionItemWithoutGetCodeMethod()
        {
            $items = [new \stdClass()];

            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Item pada index 0 harus memiliki metode getCode().');

            PaymentValidator::isCodeInItems($items, 1);
        }
    }