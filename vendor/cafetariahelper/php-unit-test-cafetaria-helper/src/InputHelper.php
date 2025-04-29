<?php 

    namespace Cafetaria\Helper;

    class InputHelper 
    {
        public static function input(string $info, ?string $input = null, $inputStream = null): string
        {
            if(trim($info) === '') 
            {
                throw new \Exception("Info tidak boleh kosong.");
            }

            echo "$info: ";

            if($input !== null)
            {
                echo $input . PHP_EOL; 
                return trim($input);
            }

            if($inputStream !== null && !is_resource($inputStream)) 
            {
                throw new \Exception("Masukan stream yang disediakan tidak valid.");
            }

            $stream = $inputStream ?? fopen('php://stdin', 'r');
            $result = fgets($stream);

            if($result === false) 
            {
                throw new \Exception("Gagal membaca masukan.");
            }
            
            if($inputStream === null) fclose($stream);
            return trim($result);
        }
    }