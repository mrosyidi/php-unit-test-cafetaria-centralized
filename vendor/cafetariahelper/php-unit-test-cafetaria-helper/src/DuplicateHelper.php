<?php 

    namespace Cafetaria\Helper;

    class DuplicateHelper 
    {
        public static function duplicate(array $orders, int $code): array 
        {
            $elements = [];

            foreach ($orders as $order) 
            {
                if(!is_object($order)) 
                {
                    throw new \Exception('Semua item order harus berupa objek.');
                }
    
                if(!method_exists($order, 'getCode')) 
                {
                    throw new \Exception('Setiap objek order harus memiliki metode getCode().');
                }
    
                if($order->getCode() === $code) 
                {
                    $elements[] = $order;
                }
            }

            return $elements;
        }
    }