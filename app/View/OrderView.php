<?php 

    namespace Cafetaria\View;

    use Cafetaria\Service\FoodService;
    use Cafetaria\Service\DrinkService;
    use Cafetaria\Service\OrderService;
    use Cafetaria\Helper\CodeHelper;
    use Cafetaria\Helper\DataHelper;
    use Cafetaria\Helper\InputHelper;
    use Cafetaria\Validator\OrderValidator;
    use Cafetaria\View\FoodRenderer;
    use Cafetaria\View\DrinkRenderer;
    use Cafetaria\View\OrderRenderer;

    class OrderView 
    {
        private FoodService $foodService;
        private DrinkService $drinkService;
        private OrderService $orderService;

        public function __construct(FoodService $foodService, DrinkService $drinkService, OrderService $orderService)
        {
            $this->foodService = $foodService;
            $this->drinkService = $drinkService;
            $this->orderService = $orderService;
        }

        public function showOrder(): void 
        {
            $open = true;

            while(true)
            {
                $orders = $this->orderService->getAllOrder();
                
                OrderRenderer::render($orders);

                echo "Menu Pemesanan" . PHP_EOL;
                echo "1. Pesan Makanan" . PHP_EOL;
                echo "2. Pesan Minuman" . PHP_EOL;
                echo "x. Kembali" . PHP_EOL;

                $pilihan = InputHelper::input("Pilih");

                if($pilihan == "1")
                {
                    $exit = $open ? true : false;
                    $this->addOrder(1, $exit);
                    $open = false;
                }else if($pilihan == "2")
                {
                    $exit = $open ? true : false;
                    $this->addOrder(2, $exit);
                    $open = false;
                }else if($pilihan == "x")
                {
                    break;
                }else
                {
                    echo "Pilihan tidak dimengerti" . PHP_EOL;
                }
            }
        }

        public function addOrder(int $numberOrder, bool $exit): void 
        {
            $orders = $this->orderService->getAllOrder();
            $payments = [];

            if($numberOrder == 1)
            {
                $order = "makanan";
                $items = $this->foodService->getAllFood();
                FoodRenderer::render($items);
            }else if($numberOrder == 2)
            {
                $order = "minuman";
                $items = $this->drinkService->getAllDrink();
                DrinkRenderer::render($items);
            }

            echo "MENAMBAH PESANAN" . PHP_EOL;

            $number = InputHelper::input("Nomor $order (x untuk batal)");

            if($number == "x")
            {
                echo "Batal menambah pesanan" . PHP_EOL;
                return;
            }
            
            if(!is_numeric($number))
            {
                echo "Gagal menambah pesanan, nomor $order harus bilangan" . PHP_EOL;
                return;
            }
            
            if(!OrderValidator::isWithinRange($items, $number))
            {
                echo "Gagal menambah pesanan, tidak ada $order dengan nomor $number" . PHP_EOL;
                return;
            }
            
            $qty = InputHelper::input("Jumlah (x untuk batal)");

            if($qty == "x")
            {
                echo "Batal menambah $order" . PHP_EOL;
                return;
            }
            
            if(!is_numeric($qty))
            {
                echo "Gagal menambah makanan, nomor $order harus bilangan" . PHP_EOL;
                return;
            }
            
            if($qty <= 0)
            {
                echo "Gagal menambah makanan, jumlah $order minimal satu" . PHP_EOL;
                return;
            }

            $code = CodeHelper::code($orders, $payments, $exit);
            $item = DataHelper::data($items, $number-1);
            $this->orderService->addOrder($code, $item["name"], $item["price"], $qty);

            echo "Sukses menambah pesanan" . PHP_EOL;
        }
    }