<?php 

    namespace Cafetaria\Validator;

    class FoodValidator 
    {
        public static function isValidName($name): bool
        {
            return trim($name) != '';
        }
    }