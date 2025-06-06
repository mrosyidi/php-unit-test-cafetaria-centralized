<?php 

    namespace Cafetaria\View;

    use Cafetaria\Service\OrderService;
    use Cafetaria\Service\PaymentService;
    use Cafetaria\Helper\InputHelper;

    class PaymentView 
    {
        private OrderService $orderService;
        private PaymentService $paymentService;

        public function __construct(OrderService $orderService, PaymentService $paymentService)
        {
            $this->orderService = $orderService;
            $this->paymentService = $paymentService;
        }

        public function showPayment(): void
        {
            while(true)
            {
                $orders = $this->orderService->getAllOrder();
                
                OrderRenderer::render($orders);

                echo "Menu Pembayaran" . PHP_EOL;
                echo "1. Bayar Pesanan" . PHP_EOL;
                echo "x. Kembali" . PHP_EOL;

                $pilihan = InputHelper::input("Pilih");

                if($pilihan == "1")
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