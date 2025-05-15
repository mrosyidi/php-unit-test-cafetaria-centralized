<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Service\DrinkServiceImpl;
    use Cafetaria\Repository\DrinkRepository;
    use Cafetaria\Entity\Drink;

    class DrinkServiceTest extends TestCase
    {
        private $drinkRepository;
        private $drinkService;

        protected function setUp(): void
        {
            $this->drinkRepository = $this->createMock(DrinkRepository::class);
            $this->drinkService = new DrinkServiceImpl($this->drinkRepository);
        }

        public function testGetAllDrinkWhenDataExist()
        {
            $drinks = [
                $this->createConfiguredMock(Drink::class, [
                    'getName' => 'Es Oyen',
                    'getPrice' => 12000
                ]),
                $this->createConfiguredMock(Drink::class, [
                    'getName' => 'Es Campur',
                    'getPrice' => 10000
                ])
            ];

            $this->drinkRepository->method('findAll')->willReturn($drinks);

            $drinks = $this->drinkService->getAllDrink();

            $this->assertCount(2, $drinks);
            $this->assertEquals('Es Oyen', $drinks[0]->getName());
            $this->assertEquals(12000, $drinks[0]->getPrice());
        }

        public function testGetAllDrinkWhenNoDataExist()
        {
            $this->drinkRepository->method('findAll')->willReturn([]);

            $drinks = $this->drinkService->getAllDrink();

            $this->assertCount(0, $drinks);
        }

        public function testAddFoodSuccess()
        {
            $this->drinkRepository->expects($this->once())
            ->method('save')->with(new Drink("Es Campur", 12000));

            $this->drinkService->addDrink("Es Campur", 12000);
        }

        public function testAddDrinkWithEmptyNameThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->drinkService->addDrink("", 12000);
        }

        public function testAddDrinkWithZeroPriceThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->drinkService->addDrink("Es Campur", 0);
        }

        public function testAddDrinkWithNegativePriceThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->drinkService->addDrink("Es Oyen", -12000);
        }

        public function testAddDrinkSaveFailsThrowsException()
        {
            $this->drinkRepository->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \PDOException("Database error")));

            $this->expectException(\PDOException::class);

            $this->drinkService->addDrink("Es Teh", 4000);
        }

        public function testRemoveDrinkReturnsTrue()
        {
            $this->drinkRepository->expects($this->once())
            ->method('remove')->with('Jus Jeruk')
            ->willReturn(true);

            $result = $this->drinkService->removeDrink('Jus Jeruk');

            $this->assertTrue($result);
        }

        public function testRemoveDrinkReturnsFalse()
        {
            $this->drinkRepository->expects($this->once())
            ->method('remove')->with('Jus Jambu')
            ->willReturn(false);

            $result = $this->drinkService->removeDrink('Jus Jambu');

            $this->assertFalse($result);
        }
    }