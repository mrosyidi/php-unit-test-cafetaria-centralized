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
    }