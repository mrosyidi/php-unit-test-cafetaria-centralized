<?php 

    namespace Cafetaria\Helper;

    class PayHelper 
    {
        public static function pay(array $orders, int $code): int
        {
            $total = 0;

            foreach ($orders as $order) 
            {
                if(!is_object($order)) 
                {
                    throw new \Exception("Semua item order harus berupa objek.");
                }
    
                if(!method_exists($order, 'getCode') || !method_exists($order, 'getSubTotal')) 
                {
                    throw new \Exception("Setiap objek order harus memiliki metode getCode() dan getSubTotal().");
                }
    
                if($order->getCode() === $code) 
                {
                    $total = $total + $order->getSubTotal();
                }
            }

            return $total;
        }
    }