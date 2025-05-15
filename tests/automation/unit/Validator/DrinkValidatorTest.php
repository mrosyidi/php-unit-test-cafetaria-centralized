<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Drink;
    use Cafetaria\Validator\DrinkValidator;

    class DrinkValidatorTest extends TestCase
    {
        public function testIsValidName()
        {
            $this->assertTrue(DrinkValidator::isValidName("Es Campur"));
            $this->assertFalse(DrinkValidator::isValidName("   "));
            $this->assertFalse(DrinkValidator::isValidName(""));
            $this->assertTrue(DrinkValidator::isValidName("   Es Oyen  "));
        }

        public function testIsDuplicateReturnsTrueWhenDuplicateExists()
        {
            $drink = $this->createMock(Drink::class);
            
            $drink->method('getName')->willReturn('Jus Alpukat');

            $drinks[] = $drink;

            $this->assertTrue(DrinkValidator::isDuplicate($drinks, 'jus alpukat'));
        }
    }