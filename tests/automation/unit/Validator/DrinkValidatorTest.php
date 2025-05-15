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

        public function testIsDuplicateReturnsFalseWhenNoDuplicate()
        {
            $drink = $this->createMock(Drink::class);
            
            $drink->method('getName')->willReturn('Es Campur');

            $drinks[] = $drink;

            $this->assertFalse(DrinkValidator::isDuplicate($drinks, 'Jus Anggur'));
        }

        public function testIsDuplicateThrowsExceptionWhenNoGetName()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Objek tidak memiliki metode getName.");

            $drinks[] = (object)['name' => 'Es Kelapa Muda'];
            
            DrinkValidator::isDuplicate($drinks, "Es kelapa Muda");
        }
    }