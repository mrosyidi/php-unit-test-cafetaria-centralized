<?php 

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Config\Database;
    use Cafetaria\Repository\FoodRepositoryImpl;
    use Cafetaria\Repository\DrinkRepositoryImpl;
    use Cafetaria\Repository\OrderRepositoryImpl;
    use Cafetaria\Service\FoodServiceImpl;
    use Cafetaria\Service\DrinkServiceImpl;
    use Cafetaria\Service\OrderServiceImpl;

    class OrderViewTest extends TestCase
    {
        private string $path;
        private FoodRepositoryImpl $foodRepository;
        private DrinkRepositoryImpl $drinkRepository;
        private OrderRepositoryImpl $orderRepository;
        private FoodServiceImpl $foodService;
        private DrinkServiceImpl $drinkService;
        private OrderServiceImpl $orderService;

        public function setUp(): void 
        {
            $this->path = realpath(__DIR__ . "/../../../../app/App.php");

            $connection = Database::getConnection();

            $this->foodRepository = new FoodRepositoryImpl($connection);
            $this->drinkRepository = new DrinkRepositoryImpl($connection);
            $this->orderRepository = new OrderRepositoryImpl($connection);

            $this->foodService = new FoodServiceImpl($this->foodRepository);
            $this->drinkService = new DrinkServiceImpl($this->drinkRepository);
            $this->orderService = new OrderServiceImpl($this->orderRepository);

            $this->orderRepository->removeAll();
            $this->foodRepository->removeAll();
            $this->drinkRepository->removeAll();
        }

        public function runCliApp(array $inputs): string
        {
            $descriptorspec = [
                0 => ["pipe", "r"],
                1 => ["pipe", "w"],
                2 => ["pipe", "w"]
            ];

            $process = proc_open("php " . $this->path , $descriptorspec, $pipes);

            if (!is_resource($process)) 
            {
                $this->fail("Gagal membuka proses App.php");
            }

            fwrite($pipes[0], implode(PHP_EOL , $inputs) . PHP_EOL);
            fclose($pipes[0]);

            $output = stream_get_contents($pipes[1]);

            fclose($pipes[1]);
            fclose($pipes[2]);

            proc_close($process);

            return $output;
        }

        public function testShowOrderWhenNoOrderExist()
        {
            $output = $this->runCliApp([
                "3",      
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("DAFTAR PESANAN", $output);
            $this->assertStringContainsString("Tidak ada daftar pesanan", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }

        public function testShowOrderWhenOrderFoodExists()
        {
            $this->foodService->addFood("Mie Ayam", 7000);
            
            $foods = $this->foodService->getAllFood();
            
            $this->orderService->addOrder(1, $foods[0]->getName(), $foods[0]->getPrice(), 1);

            $output = $this->runCliApp([
                "3",      
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("DAFTAR PESANAN", $output);
            $this->assertStringContainsString("1. 1 Mie Ayam Rp.7000 (x1) Rp.7000", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }

        public function testShowOrderWhenOrderDrinkExists()
        {
            $this->drinkService->addDrink("Es Oyen", 12000);
            
            $drinks = $this->drinkService->getAllDrink();
            
            $this->orderService->addOrder(1, $drinks[0]->getName(), $drinks[0]->getPrice(), 2);

            $output = $this->runCliApp([
                "3",      
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("DAFTAR PESANAN", $output);
            $this->assertStringContainsString("1. 1 Es Oyen Rp.12000 (x2) Rp.24000", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }

        public function testShowOrderWithInvalidMenuSelection()
        {
            $output = $this->runCliApp([
                "3",      
                "7",
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("DAFTAR PESANAN", $output);
            $this->assertStringContainsString("Tidak ada daftar pesanan", $output);
            $this->assertStringContainsString("Pilihan tidak dimengerti", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }
    }