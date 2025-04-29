<?php 

    namespace Cafetaria\Helper;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Helper\Entity\Food;
    use Cafetaria\Helper\Entity\Drink;

    class CheckHelperTest extends TestCase
    {
        public function testCheckReturnsTrueWhenItemExists()
        {
            $items = [
                new Food("Mie Goreng", 6000),
                new Food("Ayam Bakar", 12000),
                new Food("Rawon", 12000)
            ];

            $result = CheckHelper::check($items, "Ayam Bakar");
            $this->assertTrue($result);
        }

        public function testCheckIgnoresCaseAndWhitespaceWhenCheckingItem()
        {
            $items = [
                new Drink("Es Oyen", 12000),
                new Drink("Es Campur", 12000),
                new Drink("Es Kelapa Muda", 7000)
            ];

            $result = CheckHelper::check($items, "eS  Ca  m   pur");
            $this->assertTrue($result);
        }

        public function testCheckReturnsFalseWhenItemDoesNotExist()
        {
            $items = [
                new Food("Ayam Panggang", 6000),
                new Food("Soto Ayam", 12000),
                new Food("Rawon", 12000)
            ];

            $result = CheckHelper::check($items, "Gado-Gado");
            $this->assertFalse($result);
        }

        public function testCheckThrowsExceptionWhenItemsArrayIsEmpty()
        {
            $items = [];

            $this->expectException(\Exception::class);
            $this->expectExceptionMessage("Item array tidak boleh kosong.");

            CheckHelper::check($items, "Es Oyen");
        }

        public function testCheckThrowsExceptionWhenItemDoesNotHaveGetNameMethod()
        {
            $items = [
                new \stdClass()
            ];

            $this->expectException(\Exception::class);
            $this->expectExceptionMessage("Item pada index 0 tidak memiliki metode getName().");

            CheckHelper::check($items, "Es Campur");
        }
    }