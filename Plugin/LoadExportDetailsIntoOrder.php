<?php
declare(strict_types=1);




namespace SwiftOtter\OrderExport\Plugin;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use SwiftOtter\OrderExport\Model\OrderExportDetailsFactory;
use SwiftOtter\OrderExport\Model\OrderExportDetailsRepository;

class LoadExportDetailsIntoOrder
{
    /**
     * @var OrderExtensionFactory
     */
    private $extensionFactory;
    /**
     * @var OrderExportDetailsRepository
     */
    private $exportDetailsRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var OrderExportDetailsFactory
     */
    private $detailsFactory;

    public function __construct(
        OrderExtensionFactory $extensionFactory,
        OrderExportDetailsRepository $exportDetailsRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderExportDetailsFactory $detailsFactory
    ) {
        $this->extensionFactory = $extensionFactory;
        $this->exportDetailsRepository = $exportDetailsRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->detailsFactory = $detailsFactory;
    }

    public function afterGet(
        OrderRepositoryInterface $subject,
        OrderInterface $order
    ): OrderInterface {
        return $this->injectDetails($order);
    }

    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $results
    ): OrderSearchResultInterface {
        foreach($results->getItems() as $order) {
            $this->injectDetails($order);
        }
        return $results;
    }

    private function injectDetails(
        OrderInterface $order
    ): OrderInterface {
        $extensionAttributes = $order->getExtensionAttributes() ?? $this->extensionFactory->create();
        $details = $this->exportDetailsRepository->getList(
            $this->searchCriteriaBuilder->addFilter(
                'order_id',
                $order->getEntityId()
            )->create()
        )->getItems();
        if (count($details)) {
            $extensionAttributes->setOrderExportDetails(reset($details));
        } else {
            $extensionAttributes->setOrderexportDetails($this->detailsFactory->create());
        }

        $order->setExtensionAttributes($extensionAttributes);
        return $order;
    }
}
