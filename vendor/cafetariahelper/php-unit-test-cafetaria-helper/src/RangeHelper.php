<?php 

    namespace Cafetaria\Helper;

    class RangeHelper
    {
        public static function range(array $items, int $number): bool
        {
            if($number < 1) 
            {
                throw new \Exception('Bilangan harus lebih dari 0.');
            }
    
            if(empty($items)) 
            {
                throw new \Exception('Item tidak boleh kosong.');
            }
    
            return $number <= count($items);
        }
    }