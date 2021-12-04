<?php
declare(strict_types=1);





namespace SwiftOtter\OrderExport\Model\ResourceModel\OrderExportDetails;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection as AbstractCollectionAlias;
use SwiftOtter\OrderExport\Model\OrderExportDetails as OrderExportDetailsModel;
use SwiftOtter\OrderExport\Model\ResourceModel\OrderExportDetails as OrderExportDetailsResource;


class Collection extends AbstractCollectionAlias
{
    protected function _construct()
    {
        parent::_init(
            OrderExportDetailsModel::class,
            OrderExportDetailsResource::class
        );
    }

}
