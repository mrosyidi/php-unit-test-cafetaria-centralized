<?php 

    namespace Cafetaria;
    
    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Food;

    class FoodTest extends TestCase
    {
        public function testCanCreateFoodWithValidData()
        {
            $food = new Food("Rawon", 12000);
            $this->assertEquals("Rawon", $food->getName());
            $this->assertEquals(12000, $food->getPrice());
        }

        public function testEmptyNameThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Nama tidak boleh kosong.");
            new Food("   ", 12000);
        }

        public function testNegativePriceThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Harga harus lebih dari nol.");
            new Food("Mie Ayam", -7000);
        }

        public function testZeroPriceThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Harga harus lebih dari nol.");
            new Food("Soto Ayam", 0);
        }
    }