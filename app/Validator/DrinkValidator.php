<?php 

    namespace Cafetaria\Validator;

    class DrinkValidator 
    {
        public static function isValidName(string $name): bool
        {
            return trim($name) != '';
        }

        public static function isDuplicate(array $drinks, string $name): bool 
        {
            foreach ($drinks as $drink) 
            {
                if (!method_exists($drink, 'getName')) 
                {
                    throw new \InvalidArgumentException("Objek tidak memiliki metode getName.");
                }
        
                if (strtolower(trim($drink->getName())) === strtolower(trim($name))) 
                {
                    return true;
                }
            }

            return false;
        }
    }