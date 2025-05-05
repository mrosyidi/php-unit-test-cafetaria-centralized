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
    }
