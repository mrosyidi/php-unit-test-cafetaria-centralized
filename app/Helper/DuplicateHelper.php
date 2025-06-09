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
                    throw new \InvalidArgumentException('Semua item order harus berupa objek.');
                }
    
                if(!method_exists($order, 'getCode')) 
                {
                    throw new \InvalidArgumentException('Setiap objek order harus memiliki metode getCode().');
                }
    
                if($order->getCode() === $code) 
                {
                    $elements[] = $order;
                }
            }

            return $elements;
        }
    }