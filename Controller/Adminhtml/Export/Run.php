<?php
declare(strict_types=1);


namespace SwiftOtter\OrderExport\Controller\Adminhtml\Export;

use Magento\Backend\App\Action as AdminAct;
use Magento\Backend\App\Action\Context as ActionCont;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use SwiftOtter\OrderExport\Model\HeaderDataFactory;
use SwiftOtter\OrderExport\Orchestrator;

class Run extends AdminAct implements HttpPostActionInterface
{
    /** @var JsonFactory */
    private $jsonFactory;

    /** @var HeaderDataFactory */
    private $headerDataFactory;
    /**
     * @var Orchestrator
     */
    private $orchestrator;

    public function __construct(
        JsonFactory $jsonFactory,
        ActionCont $context,
        Orchestrator $orchestrator,
        HeaderDataFactory $headerDataFactory
        )
    {
        AdminAct::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->headerDataFactory = $headerDataFactory;
        $this->orchestrator = $orchestrator;
    }

    public function execute()
    {
        $headerData = $this->headerDataFactory->create();
        $headerData->setShipDate(new \DateTime($this->getRequest()->getParam('ship_date')));
        $headerData->setMerchantNotes(htmlspecialchars($this->getRequest()->getParam('merchant_notes')));

        $this->orchestrator->run(
            $this->getRequest()->getParam('order_id'),
            $headerData
        );

        return $this->jsonFactory->create([]);
    }
}
