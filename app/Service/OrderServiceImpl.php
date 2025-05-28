<?php 

    namespace Cafetaria\Service;

    use Cafetaria\Entity\Order;
    use Cafetaria\Repository\OrderRepository;
    use Cafetaria\Service\OrderService;

    class OrderServiceImpl implements OrderService
    {
        private OrderRepository $orderRepository;

        public function __construct(OrderRepository $orderRepository)
        {
            $this->orderRepository = $orderRepository;
        }

        public function getAllOrder(): array
        {
            return $this->orderRepository->findAll();
        }

        public function addOrder(int $code, string $name, int $price, int $qty): void 
        {
            $order = new Order($code, $name, $price, $qty);
            $this->orderRepository->save($order);
        }

        public function removeOrder(int $code): void
        {
            $this->orderRepository->remove($code);
        }
    }