<?php
declare(strict_types=1);





namespace SwiftOtter\OrderExport\ViewModel;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use SwiftOtter\OrderExport\Service\Order as OrderService;



class HasBeenExported implements ArgumentInterface
{
    const STATUS_SUCCESS = 1;
    const STATUS_NOT_YET = 2;
    const STATUS_ERROR = 3;

    /**
     * @var OrderService
     */
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }



    public function getExportStatus(): int
    {
        try {
            $exported = $this->orderService->get()
                ->getExtensionAttributes()
                ->getOrderExportDetails()
                ->hasBeenExported();
            return $exported
                ? self::STATUS_SUCCESS
                : self::STATUS_NOT_YET;

        } catch (NoSuchEntityException $ex) {
            return self::STATUS_ERROR;
        }
    }


    public function getExportedMessage(): Phrase
    {
        switch ($this->getExportStatus()) {
            case self::STATUS_SUCCESS:
                return __('This has been exported');
            case self::STATUS_NOT_YET:
                return __('This has not been exported');
            case self::STATUS_ERROR:
                return __('This order is invalid');

        }
    }
}
