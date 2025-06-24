<?php 

    namespace Cafetaria\View;

    use Cafetaria\Service\PaymentService;
    use Cafetaria\Service\DetailService;
    use Cafetaria\Helper\InputHelper;
    use Cafetaria\View\PaymentRenderer;
    use Cafetaria\Validator\DetailValidator;
    use Cafetaria\View\DetailRenderer;

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
                    $this->filterDetail();
                }else if($pilihan == "x")
                {
                    break;
                }else
                {
                    echo "Pilihan tidak dimengerti" . PHP_EOL;
                }
            }
        }

        public function filterDetail(): void
        {
            $code = InputHelper::input("Kode Pesanan (x untuk batal)");
            $payments = $this->paymentService->getAllPayment();

            if($code == "x")
            {
                echo "Batal melihat detail pesanan" . PHP_EOL;
                return;
            }
            
            if(empty($payments))
            {
                echo "Tidak ada daftar pembayaran" . PHP_EOL;
                return;
            }
            
            if(!DetailValidator::isCodeInItems($payments, $code))
            {
                echo "Tidak ada kode pesanan yang sesuai dengan data pembayaran" . PHP_EOL;
                return;
            }
            
            $details = $this->detailService->getAllDetail();
            DetailRenderer::render($details, $code);
        }
    }