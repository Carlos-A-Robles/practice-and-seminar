<?php
declare(strict_types=1);





namespace SwiftOtter\OrderExport\Action;

use Magento\Framework\Exception\NoSuchEntityException;

class PushDetailsToWebservice
{
    public function execute(int $orderId, int $orderDetails): void
    {
       if (rand(1, 100) < 10) {
           throw new NoSuchEntityException(__('This order is not correctly formed.'));
       }
    }
}
