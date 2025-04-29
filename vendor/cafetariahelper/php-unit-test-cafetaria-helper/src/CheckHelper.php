<?php 

    namespace Cafetaria\Helper;

    class CheckHelper
    {
        public static function check(array $items, string $name): bool
        {
            if(empty($items)) 
            {
                throw new \Exception("Item array tidak boleh kosong.");
            }

            $name = preg_replace('/\s+/', '', strtolower($name));

            foreach($items as $key => $item) 
            {
                if(!method_exists($item, 'getName')) 
                {
                    throw new \Exception("Item pada index $key tidak memiliki metode getName().");
                }

                $item_name = preg_replace('/\s+/', '', strtolower($item->getName()));

                if($item_name === $name) 
                {
                    return true;
                }
            }

            return false;
        }
    }