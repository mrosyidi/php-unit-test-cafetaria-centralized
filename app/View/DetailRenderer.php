<?php 
    namespace Cafetaria\View;

    class DetailRenderer
    {
        public static function render(array $details, int $code): void
        {
            echo "DAFTAR DETAIL" . PHP_EOL;

            if(empty($details))
            {
                echo "Tidak ada daftar detail" . PHP_EOL;
            }else
            {
                $counter = 0;
                foreach($details as $detail)
                {
                    if($detail->getCode() == $code)
                    {
                        $counter++;
                        echo "$counter. " . $detail->getCode() . " " . $detail->getName() . " Rp." . $detail->getPrice() .
                        " (x" . $detail->getQty() . ") Rp." . $detail->getSubTotal() . PHP_EOL;
                    }
                }
            }
        }
    }