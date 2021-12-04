<?php
declare(strict_types=1);






namespace SwiftOtter\OrderExport\Collector;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\Data\OrderItemInterface as OrderItemInterfaceAlias;
use SwiftOtter\OrderExport\Api\DataCollectorInterface;
use SwiftOtter\OrderExport\Model\HeaderData;
use function DI\get;

class ItemData implements DataCollectorInterface
{
    /**
     * @var string[]
     */
    private $allowedProductTypes;
    private $productCollectionFactory;

    public function __construct(array $allowedProductTypes, ProductCollectionFactory $collectionFactory)
    {
        $this->allowedProductTypes = $allowedProductTypes;
        $this->productCollectionFactory = $collectionFactory;
    }

    public function collect(OrderInterface $order, HeaderData $headerData): array
    {
        $output = [];
        $items = $order->getItems();

        $items = array_filter($items, function(OrderItemInterface $orderItem){
            return in_array(
                $this->getProductTypeFor($orderItem->getProductId()),
                $this->allowedProductTypes
            );
        });

        return array_map(function (OrderItemInterface $item) {
            return[
        "sku" => $item->getSku(),
        "qty" => $item->getQtyOrdered(),
        "item_price" => $item->getBasePrice(),
        "item_cost" => $item->getBaseCost(),
        "total" => $item->getRowTotal()
    ];
        }, $items);

    }


    /**
     * @throws NoSuchEntityException
     */
    private function getProductTypeFor(int $productId): string
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addFieldToFilter('entity_id', ['eq' => $productId]);
        $product= $collection->getFirstItem();

        if (!$product->getId()){
            throw new NoSuchEntityException(__('This product doesn\t exist'));
        }

        return (string)$product->getType();
    }
}
