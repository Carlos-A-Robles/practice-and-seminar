<?php
declare(strict_types=1);





namespace SwiftOtter\OrderExport\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb as AbstractDbAlias;

class OrderExportDetails extends AbstractDbAlias
{

    protected function _construct()
    {
        $this->_init('sale_order_export', 'id');
    }
}
