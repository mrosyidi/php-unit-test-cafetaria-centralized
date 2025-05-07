<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Validator\FoodValidator;

    class FoodValidatorTest extends TestCase
    {
        public function testIsValidName()
        {
            $this->assertTrue(FoodValidator::isValidName("Mie Ayam"));
            $this->assertFalse(FoodValidator::isValidName("   "));
            $this->assertFalse(FoodValidator::isValidName(""));
            $this->assertTrue(FoodValidator::isValidName("   Soto Ayam  "));
        }
    }