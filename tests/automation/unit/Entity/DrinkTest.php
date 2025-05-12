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

        public function testSetNameWithValidValue()
        {
            $drink = new Drink("Jus Melon");
            $drink->setName("Es Campur");
            $this->assertEquals("Es Campur", $drink->getName());
        }

        public function testSetNameWithNullValue()
        {
            $drink = new Drink("Jus Wortel", 6000);
            $drink->setName(null);
            $this->assertNull($drink->getName());
        }

        public function testSetNameWithEmptyStringThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $drink = new Drink("Jus Jambu", 7000);
            $drink->setName(" ");
        }

        public function testSetPriceWithValidValue()
        {
            $drink = new Drink("Es Kelapa Muda", 6000);
            $drink->setPrice(7000);
            $this->assertEquals(7000, $drink->getPrice());
        }

        public function testSetPriceWithNullValue()
        {
            $drink = new Drink("Jus Wortel", 6000);
            $drink->setPrice(null);
            $this->assertNull($drink->getPrice());
        }

        public function testSetPriceWithZeroThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $drink = new Drink("Jus Semangka", 7000);
            $drink->setPrice(0);
        }

        public function testSetPriceWithNegativeThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $drink = new Drink("Es Campur", 12000);
            $drink->setPrice(-10000);
        }
    }