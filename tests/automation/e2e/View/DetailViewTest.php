<?php 

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Config\Database;
    use Cafetaria\Repository\FoodRepositoryImpl;
    use Cafetaria\Repository\DrinkRepositoryImpl;
    use Cafetaria\Repository\OrderRepositoryImpl;
    use Cafetaria\Repository\PaymentRepositoryImpl;
    use Cafetaria\Repository\DetailRepositoryImpl;
    use Cafetaria\Service\FoodServiceImpl;
    use Cafetaria\Service\DrinkServiceImpl;
    use Cafetaria\Service\OrderServiceImpl;
    use Cafetaria\Service\PaymentServiceImpl;
    use Cafetaria\Service\DetailServiceImpl;

    class DetailViewTest extends TestCase
    {
        private string $path;
        private FoodRepositoryImpl $foodRepository;
        private DrinkRepositoryImpl $drinkRepository;
        private OrderRepositoryImpl $orderRepository;
        private PaymentRepositoryImpl $paymentRepository;
        private DetailRepositoryImpl $detailRepository;
        private FoodServiceImpl $foodService;
        private DrinkServiceImpl $drinkService;
        private OrderServiceImpl $orderService;
        private PaymentServiceImpl $paymentService;
        private DetailServiceImpl $detailService;

        public function setUp(): void 
        {
            $this->path = realpath(__DIR__ . "/../../../../app/App.php");

            $connection = Database::getConnection();

            $this->foodRepository = new FoodRepositoryImpl($connection);
            $this->drinkRepository = new DrinkRepositoryImpl($connection);
            $this->orderRepository = new OrderRepositoryImpl($connection);
            $this->paymentRepository = new PaymentRepositoryImpl($connection);
            $this->detailRepository = new DetailRepositoryImpl($connection);

            $this->foodService = new FoodServiceImpl($this->foodRepository);
            $this->drinkService = new DrinkServiceImpl($this->drinkRepository);
            $this->orderService = new OrderServiceImpl($this->orderRepository);
            $this->paymentService = new PaymentServiceImpl($this->paymentRepository);
            $this->detailService = new DetailServiceImpl($this->detailRepository);

            $this->orderRepository->removeAll();
            $this->foodRepository->removeAll();
            $this->drinkRepository->removeAll();
            $this->paymentRepository->removeAll();
            $this->detailRepository->removeAll();
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

        public function testShowDetaiWhenNoPaymentExist()
        {
            $output = $this->runCliApp([
                "5",      
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("DAFTAR PEMBAYARAN", $output);
            $this->assertStringContainsString("Tidak ada daftar pembayaran", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }

        public function testShowDetailWhenPaymentExists()
        {
            $this->foodService->addFood("Rawon", 12000);
            $this->foodService->addFood("Soto Ayam", 10000);
            $this->drinkService->addDrink("Es Oyen", 12000);
            $this->drinkService->addDrink("Es Campur", 12000);
            
            $foods = $this->foodService->getAllFood();
            $drinks = $this->drinkService->getAllDrink();
            
            $this->orderService->addOrder(1, $foods[0]->getName(), $foods[0]->getPrice(), 1);
            $this->orderService->addOrder(1, $foods[1]->getName(), $foods[1]->getPrice(), 1);
            $this->orderService->addOrder(1, $drinks[0]->getName(), $drinks[0]->getPrice(), 2);
            $this->orderService->addOrder(2, $drinks[1]->getName(), $drinks[1]->getPrice(), 1);

            $this->paymentService->addPayment(1, 44000, 100000, 56000);

            $output = $this->runCliApp([
                "5",      
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("DAFTAR PEMBAYARAN", $output);
            $this->assertStringContainsString("1. Kode: 1  Total: 44000  Jumlah Bayar: 100000  Kembalian: 56000", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }
    }