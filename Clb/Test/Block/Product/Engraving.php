<?php
namespace Clb\Test\Block\Product;

use \Clb\Test\Helper\Data as CustomHelper;
use Magento\Framework\Serialize\Serializer\Serialize;
/**
 * Class Engraving
 * @package Clb\Test\Block\Product
 */
class Engraving extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var CustomHelper
     */
    protected $_customHelper;


    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        Serialize $Serialize,
        CustomHelper $_customHelper,
        array $data = []
    ) {
        $this->_serialize = $Serialize;
        $this->_customHelper = $_customHelper;
        $this->customerSession = $customerSession;
        $this->cart = $context->getCartHelper()->getCart();
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * @return bool
     */
    public function canShowEngraving(): bool
    {
        return $this->_customHelper->canShowEngravingonPDP($this->getProduct());
    }
}
