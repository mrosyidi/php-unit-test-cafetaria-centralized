<?php 

    namespace Cafetaria\Helper;

    class FindHelper 
    {
        public static function find(array $items, int $code): bool
        {
            if(empty($items)) 
            {
                throw new \Exception('Item tidak boleh kosong.');
            }
    
            $found = false;
            $index = 0;

            while($index < count($items) && !$found)
            {
                $item = $items[$index];

                if(!is_object($item)) 
                {
                    throw new \Exception("Item pada index {$index} bukan objek.");
                }

                if(!method_exists($item, 'getCode'))
                {
                    throw new \Exception("Item pada index {$index} harus memiliki metode getCode().");
                }

                if($item->getCode() == $code) 
                {
                    $found = true;
                }else 
                {
                    $index = $index + 1;
                }
            }

            return $found;
        }
    }