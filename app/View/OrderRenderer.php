<?php 
   
    namespace Cafetaria\View;

    class OrderRenderer
    {
        public static function render(array $orders): void
        {
            echo "DAFTAR PESANAN" . PHP_EOL;

            if(empty($orders))
            {
                echo "Tidak ada daftar pesanan" . PHP_EOL;
            }else
            {
                foreach($orders as $number => $order)
                {
                    $number++;
                    echo "$number. " . $order->getCode() . " " . $order->getName() . " Rp." . $order->getPrice() .
                    " (x" . $order->getQty() . ") Rp." . $order->getSubTotal() . PHP_EOL;
                }
            }
        }
    }