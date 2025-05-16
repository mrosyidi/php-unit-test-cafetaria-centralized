<?php 

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Config\Database;
    use Cafetaria\Repository\DrinkRepositoryImpl;
    use Cafetaria\Service\DrinkServiceImpl;

    class DrinkViewTest extends TestCase
    {
        private string $path;
        private DrinkRepositoryImpl $drinkRepository;
        private DrinkServiceImpl $drinkService;

        public function setUp(): void 
        {
            $this->path = realpath(__DIR__ . "/../../../../app/App.php");

            $connection = Database::getConnection();

            $this->drinkRepository = new DrinkRepositoryImpl($connection);
            $this->drinkService = new DrinkServiceImpl($this->drinkRepository);

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

        public function testShowDrinkWhenNoFoodExists()
        {
            $output = $this->runCliApp([
                "2",      
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("Tidak ada daftar minuman", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }
    }