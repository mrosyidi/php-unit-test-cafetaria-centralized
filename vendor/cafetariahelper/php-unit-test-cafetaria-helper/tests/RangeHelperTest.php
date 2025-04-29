<?php   

    namespace Cafetaria\Helper;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Helper\Entity\Food;
    use Cafetaria\Helper\Entity\Drink;

    class RangeHelperTest extends TestCase
    {
        public function testRangeNumberWithinRange()
        {
            $items = [
                new Food("Mie Goreng", 6000),
                new Food("Pastel", 5000),
                new Food("Rawon", 12000)
            ];

            $this->assertTrue(RangeHelper::range($items, 2));
        }

        public function testRangeNumberEqualsCount()
        {
            $items = [
                new Drink("Jus Jeruk", 5000),
                new Drink("Es Oyen", 12000),
                new Drink("Es Campur", 12000)
            ];

            $this->assertTrue(RangeHelper::range($items, 3));
        }

        public function testRangeNumberExceedsCount()
        {
            $items = [
                new Food("Soto Ayam", 10000),
                new Food("Ayam Panggang", 12000),
                new Food("Gado-Gado", 10000)
            ];

            $this->assertFalse(RangeHelper::range($items, 4));
        }

        public function testRangeThrowsExceptionNumberZero()
        {
            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('Bilangan harus lebih dari 0.');

            $items = [
                new Drink("Es Teh", 4000),
                new Drink("Jus Alpukat", 10000),
                new Drink("Es Kelapa Muda", 7000)
            ];

            RangeHelper::range($items, 0);
        }

        public function testThrowsExceptionRangeEmptyItems()
        {
            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('Item tidak boleh kosong.');

            $items = [];
            RangeHelper::range($items, 1);
        }
    }