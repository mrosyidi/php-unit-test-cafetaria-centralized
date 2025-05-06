<?php 

    namespace Cafetaria\View;

    use Cafetaria\Service\FoodService;
    use Cafetaria\Helper\InputHelper;

    class FoodView 
    {
        private FoodService $foodService;

        public function __construct(FoodService $foodService)
        {
            $this->foodService = $foodService;
        }

        public function showFood(): void 
        {
            while(true)
            {
                echo "DAFTAR MAKANAN" . PHP_EOL;

                $foods = $this->foodService->getAllFood();


                if(empty($foods))
                {
                    echo "Tidak ada daftar makanan" . PHP_EOL;
                }else
                {
                    foreach($foods as $number => $food)
                    {
                        $number++;
                        echo "$number. " . $food->getName() . "  Rp." . $food->getPrice() . PHP_EOL;
                    }
                }

                echo "Menu Makanan" . PHP_EOL;
                echo "1. Tambah Makanan" . PHP_EOL;
                echo "2. Hapus Makanan" . PHP_EOL;
                echo "x. Kembali" . PHP_EOL;

                $pilihan = InputHelper::input("Pilih");

                if($pilihan == "1")
                {

                }else if($pilihan == "2")
                {

                }else if($pilihan == "x")
                {
                    break;
                }else 
                {
                    echo "Pilihan tidak dimengerti" . PHP_EOL;
                }
            }
        }
    }