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
    }