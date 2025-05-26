<?php 

    namespace Cafetaria\Helper;

    class CodeHelper
    {
        public static function code(array $orders, array $payments, bool $exit): int
        {
            if(empty($orders) && empty($payments))
            {
                $code = 1;
            }else if(empty($orders) && !empty($payments))
            {                    
                $max = max(array_map(fn($payment)=>$payment->getCode(), $payments));
                $code = $max + 1;
            }
            else if(!empty($orders) && !$exit)
            {
                $code = $orders[sizeof($orders)-1]->getCode();
            }else if(!empty($orders) && $exit)
            {
                $max = max(array_map(fn($order)=>$order->getCode(), $orders));

                if(!empty($payments))
                {
                    $paymentMax = max(array_map(fn($payment)=>$payment->getCode(), $payments));
                    $max = $max < $paymentMax ? $paymentMax : $max;
                }

                $code = $max + 1;
            }

            return $code;
        }
    }