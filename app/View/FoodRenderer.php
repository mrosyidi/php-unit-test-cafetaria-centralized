<?php 

    namespace Cafetaria\View;

    class FoodRenderer
    {
        public static function render(array $foods): void
        {
            echo "DAFTAR MAKANAN" . PHP_EOL;

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
        }
    }