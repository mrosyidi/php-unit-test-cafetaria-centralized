<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Service\FoodServiceImpl;
    use Cafetaria\Repository\FoodRepository;
    use Cafetaria\Entity\Food;

    class FoodServiceTest extends TestCase
    {
        private $foodRepository;
        private $foodService;

        protected function setUp(): void
        {
            $this->foodRepository = $this->createMock(FoodRepository::class);
            $this->foodService = new FoodServiceImpl($this->foodRepository);
        }

        public function testGetAllFoodWhenDataExist()
        {
            $foods = [
                $this->createConfiguredMock(Food::class, [
                    'getName' => 'Ayam Goreng',
                    'getPrice' => 12000
                ]),
                $this->createConfiguredMock(Food::class, [
                    'getName' => 'Soto Ayam',
                    'getPrice' => 10000
                ])
            ];

            $this->foodRepository->method('findAll')->willReturn($foods);

            $foods = $this->foodService->getAllFood();

            $this->assertCount(2, $foods);
            $this->assertEquals('Ayam Goreng', $foods[0]->getName());
            $this->assertEquals(12000, $foods[0]->getPrice());
        }

        public function testGetAllFoodWhenNoDataExist()
        {
            $this->foodRepository->method('findAll')->willReturn([]);

            $foods = $this->foodService->getAllFood();

            $this->assertCount(0, $foods);
        }

        public function testAddFoodSuccess()
        {
            $this->foodRepository->expects($this->once())
            ->method('save')->with(new Food("Ayam Panggang", 12000));

            $this->foodService->addFood("Ayam Panggang", 12000);
        }

        public function testAddFoodWithEmptyNameThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->foodService->addFood("", 12000);
        }

        public function testAddFoodWithZeroPriceThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->foodService->addFood("Rawon", 0);
        }

        public function testAddFoodWithNegativePriceThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->foodService->addFood("Rawon", -12000);
        }
    }
