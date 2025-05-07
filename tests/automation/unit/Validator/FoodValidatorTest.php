<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Food;
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

        public function testIsDuplicateReturnsTrueWhenDuplicateExists()
        {
            $food = $this->createMock(Food::class);
            
            $food->method('getName')->willReturn('Nasi Goreng');

            $foods[] = $food;

            $this->assertTrue(FoodValidator::isDuplicate($foods, 'nasi goreng'));
        }

        public function testIsDuplicateReturnsFalseWhenNoDuplicate()
        {
            $food = $this->createMock(Food::class);
            
            $food->method('getName')->willReturn('Nasi Goreng');

            $foods[] = $food;

            $this->assertFalse(FoodValidator::isDuplicate($foods, 'Soto Ayam'));
        }

        public function testIsDuplicateThrowsExceptionWhenNoGetName()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Objek tidak memiliki metode getName.");

            $foods[] = (object)['name' => 'Mie Ayam'];
            
            FoodValidator::isDuplicate($foods, "Mie Ayam");
        }
    }