<?php
declare(strict_types=1);





namespace SwiftOtter\OrderExport\Action;

use Magento\Sales\Api\OrderRepositoryInterface;
use SwiftOtter\OrderExport\Api\DataCollectorInterface;
use SwiftOtter\OrderExport\Model\HeaderData;

class OrderToArray
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var DataCollectorInterface
     */
    private $dataCollectors;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        array $dataCollectors
    ){

        $this->orderRepository = $orderRepository;
        $this->dataCollectors = $dataCollectors;
    }

    public function execute(
        int $orderId,
        HeaderData $headerData
    ) {
        $order = $this->orderRepository->get($orderId);

        $output = [];

        foreach ($this->dataCollectors as $collector){
            if (!($collector instanceof DataCollectorInterface)){
                continue;
            }

            $output = array_merge($output, $collector->collect($order, $headerData));
        }

        return $output;
    }
}
