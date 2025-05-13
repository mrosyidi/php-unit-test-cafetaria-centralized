<?php 

    namespace Cafetaria\View;

    use Cafetaria\Service\DrinkService;
    use Cafetaria\Helper\InputHelper;

    class DrinkView 
    {
        private DrinkService $drinkService;

        public function __construct(DrinkService $drinkService)
        {
            $this->drinkService = $drinkService;
        }

        public function showDrink(): void 
        {
            while(true)
            {
                echo "DAFTAR MINUMAN" . PHP_EOL;

                $drinks = $this->drinkService->getAllDrink();


                if(empty($drinks))
                {
                    echo "Tidak ada daftar minuman" . PHP_EOL;
                }else
                {
                    foreach($drinks as $number => $drink)
                    {
                        $number++;
                        echo "$number. " . $drink->getName() . "  Rp." . $drink->getPrice() . PHP_EOL;
                    }
                }

                echo "Menu Minuman" . PHP_EOL;
                echo "1. Tambah Minuman" . PHP_EOL;
                echo "2. Hapus Minuman" . PHP_EOL;
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