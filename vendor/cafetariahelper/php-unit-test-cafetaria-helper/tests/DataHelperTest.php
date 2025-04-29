<?php   

    namespace Cafetaria\Helper;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Helper\Entity\Food;
    use Cafetaria\Helper\Entity\Drink;

    class DataHelperTest extends TestCase
    {
        public function testDataReturnsCorrectArray()
        {
            $items = [
                new Food("Mie Goreng", 6000),
                new Food("Ayam Panggang", 12000),
                new Food("Pastel", 6000)
            ];

            $result = DataHelper::data($items, 1);

            $this->assertEquals(["name" => "Ayam Panggang", "price" => 12000], $result);
        }

        public function testDataThrowsExceptionEmptyItems()
        {
            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('Item tidak boleh kosong.');

            DataHelper::data([], 0);
        }

        public function testDataThrowsExceptionIndexOutOfBounds()
        {
            $items = [
                new Drink("Es Teh", 4000),
                new Drink("Jus Alpukat", 10000),
                new Drink("Es Oyen", 12000)
            ];

            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('Index berada diluar jangkauan.');

            DataHelper::data($items, 3);
        }

        public function testDataThrowsExceptionItemIsNotObject()
        {
            $items = ["Bukan objek"];

            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('Item dalam index harus berupa objek.');

            DataHelper::data($items, 0);
        }

        public function testDataThrowsExceptionItemMissingMethods()
        {
            $items = [new \stdClass()];

            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('Item harus memiliki metode getName() dan getPrice().');

            DataHelper::data($items, 0);
        }
    }