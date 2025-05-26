<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Food;
    use Cafetaria\Validator\OrderValidator;

    class OrderValidatorTest extends TestCase
    {
        public function testNumberWithinRangeShouldReturnTrue()
        {
            $items = [1, 2, 3];
            $this->assertTrue(OrderValidator::isWithinRange($items, 2));
        }

        public function testNumberEqualToCountShouldReturnTrue()
        {
            $items = [1, 2, 3];
            $this->assertTrue(OrderValidator::isWithinRange($items, 3));
        }
    }