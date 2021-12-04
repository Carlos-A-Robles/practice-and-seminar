<?php
declare(strict_types=1);


namespace SwiftOtter\OrderExport\Model;

class HeaderData
{
    /** @var \DateTime */
    private $shipDate;

    /** @var string */
    private $merchantNotes;

    /** 
     * @return \DateTime
     */

    
    public function getShipDate(): \DateTime
    {
        return $this->shipDate;
    }

    /**
     * @param \DateTime $shipDate
     */

    public function setShipDate(\DateTime $shipDate): void
    {
        $this->shipDate = $shipDate;
    }

    /**
     * @return string
     */

    public function getMerchantNotes(): string
    {
        return $this->merchantNotes;
    } 

    /**
     * @param string $merchantNotes
     */

    public function setMerchantNotes(string $merchantNotes): void
    {
        $this->merchantNotes = $merchantNotes;
    } 
}