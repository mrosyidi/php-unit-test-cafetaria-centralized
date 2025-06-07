<?php 

    namespace Cafetaria\Service;

    use \Cafetaria\Entity\Payment;
    use \Cafetaria\Repository\PaymentRepository;

    class PaymentServiceImpl implements PaymentService 
    {
        private PaymentRepository $paymentRepository;

        public function __construct(PaymentRepository $paymentRepository)
        {
            $this->paymentRepository = $paymentRepository;
        }

        public function getAllPayment(): array 
        {
            return $this->paymentRepository->findAll();
        }

        public function addPayment(int $code, int $total, int $pay): void
        {
            $payment = new Payment($code, $total, $pay);
            $this->paymentRepository->save($payment);
        }
    }