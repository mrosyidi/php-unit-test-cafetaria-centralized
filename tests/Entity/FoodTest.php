<?php 

    namespace Cafetaria;
    
    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Food;

    class FoodTest extends TestCase
    {
        public function testConstructorWithValidValues()
        {
            $food = new Food("Rawon", 12000);
            $this->assertEquals("Rawon", $food->getName());
            $this->assertEquals(12000, $food->getPrice());
        }

        public function testConstructWithNullValues()
        {
            $food = new Food();
            $this->assertNull($food->getName());
            $this->assertNull($food->getPrice());
        }

        public function testConstructorWithEmptyStringThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Nama tidak boleh kosong.");
            new Food("   ", 12000);
        }

        public function testConstructorWithZeroPriceThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Harga harus lebih dari nol.");
            new Food("Soto Ayam", 0);
        }

        public function testConstructorWithNegativePriceThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Harga harus lebih dari nol.");
            new Food("Mie Ayam", -7000);
        }

        public function testSetNameWithValidValue()
        {
            $food = new Food("Ayam Goreng");
            $food->setName("Ayam Panggang");
            $this->assertEquals("Ayam Panggang", $food->getName());
        }

        public function testSetNameWithNullValue()
        {
            $food = new Food("Gado-Gado", 10000);
            $food->setName(null);
            $this->assertNull($food->getName());
        }

        public function testSetNameWithEmptyStringThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $food = new Food("Soto Daging", 15000);
            $food->setName(" ");
        }
        
        public function testSetPriceWithValidValue()
        {
            $food = new Food("Somay", 10000);
            $food->setPrice(12000);
            $this->assertEquals(12000, $food->getPrice());
        }

        public function testSetPriceWithNullValue()
        {
            $food = new Food("Pastel", 5000);
            $food->setPrice(null);
            $this->assertNull($food->getPrice());
        }

        public function testSetPriceWithZeroThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $food = new Food("Bebek Goreng", 15000);
            $food->setPrice(0);
        }

        public function testSetPriceWithNegativeThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $food = new Food("Bebek Bakar", 15000);
            $food->setPrice(-1000);
        }
    }