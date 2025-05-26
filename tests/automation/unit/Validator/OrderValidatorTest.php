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

        public function testZeroNumberShouldReturnFalse()
        {
            $items = [1, 2, 3];
            $this->assertFalse(OrderValidator::isWithinRange($items, 0));
        }

        public function testNegativeNumberShouldReturnFalse()
        {
            $items = [1, 2];
            $this->assertFalse(OrderValidator::isWithinRange($items, -1));
        }

        public function testNumberGreaterThanCountShouldReturnFalse()
        {
            $items = [1, 2, 3];
            $this->assertFalse(OrderValidator::isWithinRange($items, 5));
        }

        public function testEmptyItemsArrayShouldReturnFalse()
        {
            $items = [];
            $this->assertFalse(OrderValidator::isWithinRange($items, 1));
        }
    }