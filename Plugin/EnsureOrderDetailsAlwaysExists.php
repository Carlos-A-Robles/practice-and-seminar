<?php
declare(strict_types=1);





namespace SwiftOtter\OrderExport\Plugin;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderInterfaceFactory;

class EnsureOrderDetailsAlwaysExists
{
    public function afterCreate(OrderInterfaceFactory $subject, OrderInterface $order)
    {
        $extensionAttributes = $order->getExtensionAttributes() ?? $this->extensionFactory->create();
        $extensionAttributes->setOrderExportDetails($this->detailsFactory->create());

        $order->setExtensionAttributes($extensionAttributes);
        return $order;
    }
}
