<?php
declare(strict_types=1);





namespace SwiftOtter\OrderExport\Service;


use Magento\Framework\App\RequestInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class Order
{
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    public function __construct(RequestInterface $request, OrderRepositoryInterface $orderRepository)
    {

        $this->request = $request;
        $this->orderRepository = $orderRepository;
    }

    public function get(): OrderInterface
    {
        return $this->orderRepository->get(
            (int)$this->request->getParam('order_id')
        );
    }
}
