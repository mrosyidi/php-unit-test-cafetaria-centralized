<?php 
   
    namespace Cafetaria\View;

    class PaymentRenderer
    {
        public static function render(array $payments): void
        {
            echo "DAFTAR PEMBAYARAN" . PHP_EOL;

            if(empty($payments))
            {
                echo "Tidak ada daftar pembayaran" . PHP_EOL;
            }else
            {
                foreach($payments as $number => $payment)
                {
                    $number++;
                    echo "$number. Kode: " . $payment->getCode() . "  Total: " . $payment->getTotal() .
                    "  Jumlah Bayar: " . $payment->getPay() . "  Kembalian: " . $payment->getChange() . PHP_EOL;
                }
            }
        }
    }