<?php
declare(strict_types=1);

namespace SwiftOtter\OrderExport\Setup\Patch\Data;

use Amasty\CommonRules\Model\Rule\Condition\Product;
use ClassyLlama\AvaTax\Model\Config\Source\Product\Attributes;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory as EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
* Patch is mechanism, that allows to do atomic upgrade data changes
*/
class CreateSkuOverrideAttribute implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;
    private $eavSetupFactory;
    private $eavSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Do Upgrade
     *
     * @return void
     */
    public function apply()
    {
        /**@var EavSetup $eavSetup*/
        $eavSetup = $this->eavSetupFactory->create(['$setup' => $this->moduleDataSetup]);
        $this->eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, Attributes::SKU_OVERRIDE_ATTRIBUTE, [
            'type' => 'varchar',
            'label' => 'SKU Override',
            'input' => 'text',
            'required' => 'false',
            'class' => 'not-negative-amount',
            'sort_order' => 66,
            'scope' => ScopedAttributeInterface::SCOPE_WEBSITE,
            'is_used_in_grid' => false,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => false,
            'user_defined' => false,
            'used_in_product_listing' => true

    ]);
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

}
