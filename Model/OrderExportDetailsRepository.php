<?php
declare(strict_types=1);





namespace SwiftOtter\OrderExport\Model;

use Magento\Cms\Api\Data;

use Magento\Cms\Api\Data\BlockInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use SwiftOtter\OrderExport\Api\Data\OrderExportDetailsInterface;
use SwiftOtter\OrderExport\Api\Data\OrderExportDetailsSearchResultsInterface;
use SwiftOtter\OrderExport\Api\Data\OrderExportDetailsSearchResultsInterfaceFactory;
use SwiftOtter\OrderExport\Model\ResourceModel\OrderExportDetails as OrderExportDetailsResource;
use SwiftOtter\OrderExport\Model\ResourceModel\OrderExportDetails\CollectionFactory as DetailsCollectionFactory;



class OrderExportDetailsRepository
{
    /** @var OrderExportDetailsResource */
    protected $resource;

    /** @var OrderExportDetailsFactory */
    protected $detailsFactory;

    /**
     * @var DetailsCollectionFactory
     */
    protected $detailsCollectionFactory;

    /**
     * @var Data\BlockSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;



    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;


    public function __construct(
        OrderExportDetailsResource                      $resource,
        OrderExportDetailsFactory                       $detailsFactory,
        DetailsCollectionFactory                        $detailsCollectionFactory,
        OrderExportDetailsSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface                    $collectionProcessor

    )
    {
        $this->resource = $resource;
        $this->detailsFactory = $detailsFactory;
        $this->detailsCollectionFactory = $detailsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function save(OrderExportDetailsInterface $orderExportDetails)
    {
        try {
            $this->resource->save($orderExportDetails);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $orderExportDetails;
    }

    public function getById($detailsId)
    {
        $details = $this->detailsFactory->create();
        $this->resource->load($details, $detailsId);
        if (!$details->getId()) {
            throw new NoSuchEntityException(__('The details block with the "%1" ID doesn\'t exist.', $detailsId));
        }
        return $detailsId;
    }

    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var OrderExportDetailsResource $collection */
        $collection = $this->detailsCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var OrderExportDetailsSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    public function delete(OrderExportDetailsInterface $orderExportDetails)
    {
        try {
            $this->resource->delete($orderExportDetails);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    public function deleteById($detailsId)
    {
        return $this->delete($this->getById($detailsId));
    }
}

