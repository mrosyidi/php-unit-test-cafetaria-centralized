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
    }