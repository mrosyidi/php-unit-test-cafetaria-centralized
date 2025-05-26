<?php 

    namespace Cafetaria\Helper;

    class DataHelper 
    {
        public static function data(array $items, int $index): array
        {
            if(empty($items)) 
            {
                throw new \Exception('Item tidak boleh kosong.');
            }
    
            if(!isset($items[$index])) 
            {
                throw new \Exception('Index berada diluar jangkauan.');
            }
    
            $item = $items[$index];
    
            if(!is_object($item)) 
            {
                throw new \Exception('Item dalam index harus berupa objek.');
            }
    
            if(!method_exists($item, 'getName') || !method_exists($item, 'getPrice')) 
            {
                throw new \Exception('Item harus memiliki metode getName() dan getPrice().');
            }
    
            return [
                'name'  => $item->getName(),
                'price' => $item->getPrice(),
            ];
        }
    }