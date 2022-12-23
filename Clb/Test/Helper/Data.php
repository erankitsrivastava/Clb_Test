<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Clb\Test\Helper;

use Magento\Catalog\Model\Product\Type as ProductType;
use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const ENGRAVING_ENABLE_CONFIG_PATH = 'catalog/frontend/display_engraving_option';

    const ALLOWED_PRODUCT_TYPE = [
        ProductType::TYPE_SIMPLE,
        ProductType::TYPE_VIRTUAL,
    ];

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
        $this->scopeConfig = $context->getScopeConfig();
    }

    /**
     * @return array
     */
    public function getAllowedProductTypes(){
        return self::ALLOWED_PRODUCT_TYPE;
    }

    /**
     * @return bool
     */
    public function isEngravingEnable(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::ENGRAVING_ENABLE_CONFIG_PATH);
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return bool
     */
    public function canShowEngravingonPDP(\Magento\Catalog\Model\Product $product): bool
    {
        return $this->isEngravingEnable() &&
            in_array($product->getTypeId(), $this->getAllowedProductTypes());
    }
}
