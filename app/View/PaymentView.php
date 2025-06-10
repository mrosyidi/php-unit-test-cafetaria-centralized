<?php 

    namespace Cafetaria\View;

    use Cafetaria\Service\OrderService;
    use Cafetaria\Service\PaymentService;
    use Cafetaria\Helper\DuplicateHelper;
    use Cafetaria\Helper\InputHelper;
    use Cafetaria\Helper\PayHelper;
    use Cafetaria\Validator\PaymentValidator;

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
                    $this->addPayment();
                }else if($pilihan == "x")
                {
                    break;
                }else
                {
                    echo "Pilihan tidak dimengerti" . PHP_EOL;
                }
            }
        }

        public function addPayment(): void 
        {
            echo "PEMBAYARAN PESANAN" . PHP_EOL;
            $code = InputHelper::input("Kode Pesanan (x untuk batal)");

            if($code == "x")
            {
                echo "Batal memproses pesanan." . PHP_EOL;
                return;
            }

            if(!is_numeric($code))
            {
                echo "Gagal memproses pesanan, kode pesanan harus bilangan." . PHP_EOL;
                return;
            }

            $orders = $this->orderService->getAllOrder();

            if(empty($orders))
            {
                echo "Gagal memproses pesanan, tidak ada item yang dipesan." . PHP_EOL;
                return;
            }

            $codeOrder = PaymentValidator::isCodeInItems($orders, $code);

            if(!$codeOrder)
            {
                echo "Gagal memproses pesanan, kode pesanan tidak ditemukan." . PHP_EOL;
                return;
            }

            $pay = PayHelper::pay($orders, $code);
            echo "Total yang harus dibayar : Rp." . $pay . PHP_EOL;
            
            $money = InputHelper::input("Jumlah uang (x untuk batal)");

            if($money == "x")
            {
                echo "Batal memproses pesanan." . PHP_EOL;
                return;
            }

            if(!is_numeric($money))
            {
                echo "Gagal memproses pesanan, jumlah uang harus bilangan." . PHP_EOL;
                return;
            }
            
            if($money < $pay)
            {
                echo "Gagal memproses pesanan, jumlah uang yang digunakan tidak cukup." . PHP_EOL;
                return;
            }
            
            $change = $money-$pay;
            $elements = DuplicateHelper::duplicate($orders, $code);

            $this->paymentService->addPayment($code, $pay, $money);
            $this->orderService->removeOrder($code);
            
            echo "Kembalian : Rp." . $change . PHP_EOL;
        }
    }