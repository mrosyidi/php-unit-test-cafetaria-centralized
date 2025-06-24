<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Detail;
    use Cafetaria\Validator\DetailValidator;

    class DetailValidatorTest extends TestCase
    {
        public function testIsCodeInItemsReturnsTrueWhenItemFound()
        {
            $items = [
                new Detail(1, 50000),
                new Detail(2, 22000),
                new Detail(3, 35000)
            ];

            $result = DetailValidator::isCodeInItems($items, 2);

            $this->assertTrue($result);
        }

        public function testIsCodeInItemsReturnsFalseWhenItemNotFound()
        {
            $items = [
                new Detail(1, 50000),
                new Detail(2, 22000),
                new Detail(3, 35000)
            ];

            $result = DetailValidator::isCodeInItems($items, 4);

            $this->assertFalse($result);
        }

        public function testIsCodeInItemsThrowsExceptionEmptyItemsArray()
        {
            $items = [];

            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Item tidak boleh kosong.');
            
            DetailValidator::isCodeInItems($items, 1);
        }

        public function testIsCodeInItemsThrowsExceptionItemNotObject()
        {
            $items = ["Bukan objek"];

            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Item pada index 0 bukan objek.');

            DetailValidator::isCodeInItems($items, 1);
        }

        public function testIsCodeInItemsThrowsExceptionItemWithoutGetCodeMethod()
        {
            $items = [new \stdClass()];

            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Item pada index 0 harus memiliki metode getCode().');

            DetailValidator::isCodeInItems($items, 1);
        }
    }