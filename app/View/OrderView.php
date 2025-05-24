<?php 

    namespace Cafetaria\View;

    use Cafetaria\Service\OrderService;
    use Cafetaria\Helper\InputHelper;
    use Cafetaria\Validator\OrderValidator;

    class OrderView 
    {
        private OrderService $orderService;

        public function __construct(OrderService $orderService)
        {
            $this->orderService = $orderService;
        }

        public function showOrder(): void 
        {
            while(true)
            {
                echo "DAFTAR PESANAN" . PHP_EOL;

                $orders = $this->orderService->getAllOrder();


                if(empty($orders))
                {
                    echo "Tidak ada daftar pesanan" . PHP_EOL;
                }else
                {
                    foreach($orders as $number => $order)
                    {
                        $number++;
                        echo "$number. " . $order->getCode() . " " . $order->getName() . " Rp." . $order->getPrice() .
                        " (x" . $order->getQty() . ") Rp." . $order->getSubTotal() . PHP_EOL;
                    }
                }

                echo "Menu Pemesanan" . PHP_EOL;
                echo "1. Pesan Makanan" . PHP_EOL;
                echo "2. Pesan Minuman" . PHP_EOL;
                echo "x. Kembali" . PHP_EOL;

                $pilihan = InputHelper::input("Pilih");

                if($pilihan == "1")
                {

                }else if($pilihan == "2")
                {
                    
                }else if($pilihan == "x")
                {
                    break;
                }else
                {
                    echo "Pilihan tidak dimengerti" . PHP_EOL;
                }
            }
        }
    }