<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Config\Database;
    use Cafetaria\Entity\Drink;
    use Cafetaria\Repository\DrinkRepositoryImpl;
    use Cafetaria\Service\DrinkServiceImpl;
    use Cafetaria\Exception\InvalidDrinkException;

    class DrinkServiceIntegrationTest extends TestCase 
    {
        private $drinkRepository;
        private $drinkService;

        public function setUp(): void 
        {
            $connection = Database::getConnection();
            $this->drinkRepository = new DrinkRepositoryImpl($connection);
            $this->drinkService = new DrinkServiceImpl($this->drinkRepository);
            $this->drinkRepository->removeAll();
        }

        public function testGetAllDrinkWithData()
        {
            $drink = new Drink("Jus Melon", 7000);
            
            $this->drinkRepository->save($drink);

            $drinks = $this->drinkService->getAllDrink();

            $this->assertCount(1, $drinks);
            $this->assertEquals($drink->getName(), $drinks[0]->getName());
            $this->assertEquals($drink->getPrice(), $drinks[0]->getPrice());
        }

        public function testGetAllDrinkWithNoData()
        {
            $drinks = $this->drinkService->getAllDrink();

            $this->assertEmpty($drinks);
        }

        public function testAddDrinkSuccess()
        {
            $this->drinkService->addDrink("Es Oyen", 12000);

            $drinks = $this->drinkService->getAllDrink();

            $this->assertCount(1, $drinks);
            $this->assertEquals("Es Oyen", $drinks[0]->getName());
            $this->assertEquals(12000, $drinks[0]->getPrice());
        }

        public function testAddDrinkWithEmptyNameThrowsException()
        {
            $this->expectException(InvalidDrinkException::class);
            $this->expectExceptionMessage("Nama tidak boleh kosong.");

            $this->drinkService->addDrink("", 10000); 
        }

        public function testAddDrinkWithZeroPriceThrowsException()
        {
            $this->expectException(InvalidDrinkException::class);
            $this->expectExceptionMessage("Harga harus lebih dari nol.");

            $this->drinkService->addDrink("Es Teh", 0); 
        }

        public function testAddDrinkWithNegativePriceThrowsException()
        {
            $this->expectException(InvalidDrinkException::class);
            $this->expectExceptionMessage("Harga harus lebih dari nol.");

            $this->drinkService->addDrink("Es Campur", -12000); 
        }

        public function testRemoveDrinkSuccess()
        {
            $this->drinkService->addDrink("Es Kelapa Muda", 5000);
            
            $drinks = $this->drinkService->getAllDrink();
            
            $this->assertCount(1, $drinks);

            $result = $this->drinkService->removeDrink("Es Kelapa Muda");

            $this->assertTrue($result);

            $drinks = $this->drinkService->getAllDrink();

            $this->assertCount(0, $drinks);
        }

        public function testRemoveDrinkReturnsFalseWhenFoodNotExist()
        {
            $result = $this->drinkService->removeDrink("Jus Wortel");
            
            $this->assertFalse($result);
        }
    }