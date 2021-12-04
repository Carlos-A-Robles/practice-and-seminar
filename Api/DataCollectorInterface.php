<?php
declare(strict_types=1);





namespace SwiftOtter\OrderExport\Api;

use Magento\Sales\Api\Data\OrderInterface;
use SwiftOtter\OrderExport\Model\HeaderData;

interface DataCollectorInterface
{
    public function collect(OrderInterface $order, HeaderData $headerData): array;
}
