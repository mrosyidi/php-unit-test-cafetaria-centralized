<?php 

    namespace Cafetaria\View;

    use Cafetaria\Service\PaymentService;
    use Cafetaria\Service\DetailService;
    use Cafetaria\Helper\InputHelper;
    use Cafetaria\View\PaymentRenderer;

    class DetailView
    {
        private PaymentService $paymentService;
        private DetailService $detailService;

        public function __construct(DetailService $detailService, PaymentService $paymentService)
        {
            $this->detailService = $detailService;
            $this->paymentService = $paymentService;
        }

        public function showDetail(): void
        {
            while(true)
            {
                $payments = $this->paymentService->getAllPayment();

                PaymentRenderer::render($payments);

                echo "Menu Detail" . PHP_EOL;
                echo "1. Tampilkan Detail" . PHP_EOL;
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