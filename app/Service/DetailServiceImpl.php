<?php 

    namespace Cafetaria\Service;

    use \Cafetaria\Entity\Detail;
    use \Cafetaria\Repository\DetailRepository;

    class DetailServiceImpl implements DetailService
    {
        private DetailRepository $detailRepository;

        public function __construct(DetailRepository $detailRepository)
        {
            $this->detailRepository = $detailRepository;
        }

        public function getAllDetail(): array
        {
            return $this->detailRepository->findAll();
        }

        public function addDetail(array $items): void
        {
            for($index = 0; $index < sizeof($items); $index++)
            {
                $code = $items[$index]->getCode();
                $name = $items[$index]->getName();
                $price = $items[$index]->getPrice();
                $qty = $items[$index]->getQty();
                $sub_total = $items[$index]->getSubTotal();
                $detail = new Detail($code, $name, $price, $qty, $sub_total);
                $this->detailRepository->save($detail);
            }
        }
    }