<?php 

    namespace Cafetaria\Helper;

    class InputHelper
    {
        public static mixed $inputStream = STDIN;

        public static function input(string $info): string
        {
            if(trim($info) === '') 
            {
                throw new \InvalidArgumentException("Info tidak boleh kosong.");
            }

            if (!is_resource(self::$inputStream)) {
                throw new \RuntimeException("Stream tidak valid.");
            }    

            echo "$info: ";

            $result = fgets(self::$inputStream);

            if($result === false)
            {
                throw new \RuntimeException("Gagal membaca masukan.");
            }

            return trim($result);
        }
    }
