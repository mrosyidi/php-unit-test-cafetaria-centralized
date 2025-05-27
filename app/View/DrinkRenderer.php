<?php 

    namespace Cafetaria\View;

    class DrinkRenderer
    {
        public static function render(array $drinks): void
        {
            echo "DAFTAR MINUMAN" . PHP_EOL;

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
        }
    }