<?php 

    namespace Cafetaria\Validator;

    class FoodValidator 
    {
        public static function isValidName(string $name): bool
        {
            return trim($name) != '';
        }

        public static function isDuplicate(array $foods, string $name): bool 
        {
            foreach ($foods as $food) 
            {
                if (!method_exists($food, 'getName')) 
                {
                    throw new \InvalidArgumentException("Objek tidak memiliki metode getName.");
                }
        
                if (strtolower(trim($food->getName())) === strtolower(trim($name))) 
                {
                    return true;
                }
            }

            return false;
        }
    }