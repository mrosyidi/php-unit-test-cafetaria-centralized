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
    }