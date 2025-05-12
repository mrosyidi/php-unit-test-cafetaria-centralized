<?php 

    namespace Cafetaria;
    
    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Drink;

    class DrinkTest extends TestCase
    {
        public function testConstructorWithValidValues()
        {
            $drink = new Drink("Es Campur", 12000);
            $this->assertEquals("Es Campur", $drink->getName());
            $this->assertEquals(12000, $drink->getPrice());
        }

        public function testConstructWithNullValues()
        {
            $drink = new Drink();
            $this->assertNull($drink->getName());
            $this->assertNull($drink->getPrice());
        }

        public function testConstructorWithEmptyStringThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Nama tidak boleh kosong.");
            new Drink("   ", 12000);
        }

        public function testConstructorWithZeroPriceThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Harga harus lebih dari nol.");
            new Drink("Es Oyen", 0);
        }

        public function testConstructorWithNegativePriceThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Harga harus lebih dari nol.");
            new Drink("Mie Ayam", -7000);
        }
    }