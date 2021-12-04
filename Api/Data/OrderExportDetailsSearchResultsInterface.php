<?php
declare(strict_types=1);





namespace SwiftOtter\OrderExport\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;


interface OrderExportDetailsSearchResultsInterface extends SearchResultsInterface
{
    /** @return \SwiftOtter\OrderExport\Api\Data\OrderExportDetailsInterface[] */
    public function getItems();

    /**
     * Set blocks list.
     *
     * @param \SwiftOtter\OrderExport\Api\Data\OrderExportDetailsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
