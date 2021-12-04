<?php
declare(strict_types=1);





namespace SwiftOtter\OrderExport\Collector;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderAddressRepositoryInterface;
use SwiftOtter\OrderExport\Api\DataCollectorInterface;

class HeaderData implements DataCollectorInterface
{
    /**
     * @var scopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var OrderAddressRepositoryInterface
     */
    private $orderAddressRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    public function __construct(
        scopeConfigInterface $scopeConfig,
        OrderAddressRepositoryInterface $orderAddressRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->orderAddressRepository = $orderAddressRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function collect(OrderInterface $order, \SwiftOtter\OrderExport\Model\HeaderData $headerData): array
    {
        $output = [
            "password" => $this->scopeConfig->getValue('sales/order_export/password'),
            "id" => $order->getIncrementId(),
            "currency" => $order->getBaseCurrencyCode(),
            "customer_notes" => $order->getExtensionAttributes()->getBoldOrderComment(),
            "merchant_notes" => $headerData->getMerchantNotes(),
            "discount" => $order->getBaseDiscountAmount(),
            "total" => $order->getBaseGrandTotal()
        ];

        $address = $this->getShippingAddress($order);

        if ($address){
            $output["shipping"] = [
                "name" => $address->getFirstname() . ' ' . $address->getLastname(),
                "address" => $address->getStreet(),
                "city" => $address->getCity(),
                "state" => $address->getRegionCode(),
                "postcode" => $address->getPostcode(),
                "country" => $address->getCountryId(),
                "amount" => $order->getBaseShippingAmount(),
                "method" => $order->getShippingDescription(),
                "ship_on" => $headerData->getShipDate()->format(Y-m-d)
            ];
        }

        return $output;
    }

    private function getShippingAddress(OrderInterface $order): ?OrderAddressInterface
    {
        $searchCriteria = $this->searchCriteriaBuilder->
        addFilter('parent_id', $order->getEntityId())
            ->addFilter('address_type', 'shipping')
            ->create();

        $addresses = $this->orderAddressRepository->getList($searchCriteria);

        if(count($addresses)){
            return reset($addresses);
        } else {
            return null; /**return count($addresses) ? reset($addresses) : null;*/
        }
    }
}
