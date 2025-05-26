<?php 

    namespace Cafetaria\Validator;

    class OrderValidator 
    {
        public static function isWithinRange(array $items, int $number): bool
        {
            return $number > 0 && $number <= count($items);
        }
    }
    