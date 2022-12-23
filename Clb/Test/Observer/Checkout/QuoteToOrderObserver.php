<?php

namespace Clb\Test\Observer\Checkout;

use \Clb\Test\Helper\Data as CustomHelper;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * Class QuoteToOrderObserver
 * @package Clb\Test\Observer\Checkout
 */
class QuoteToOrderObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $serialize;

    /**
     * @param CustomHelper $_customHelper
     * @param \Magento\Framework\Serialize\Serializer\Json $serialize
     */
    public function __construct(
        CustomHelper $_customHelper,
        \Magento\Framework\Serialize\Serializer\Json $serialize
    ) {
        $this->_customHelper = $_customHelper;
        $this->serialize = $serialize;
    }

    /**
     * @param Observer $observer
     * @return $this|void
     */
    public function execute(Observer $observer) {
        try{
            /** @var OrderInterface $order */
            $order = $observer->getOrder();

            foreach ($order->getItems() as $orderItem) {
                /** @var \Magento\Sales\Api\Data\OrderItemInterface $orderItem */
                $product = $orderItem->getProduct();
                if($this->_customHelper->canShowEngravingonPDP($product)){
                    $options = $orderItem->getProductOptions();
                    if (isset($options['info_buyRequest']) && !empty($options['info_buyRequest'])) {
                        /*@todo: add code to merge the another additional options */
                        $additionalOptions = [];
                        $option = $options['info_buyRequest'];
                        if(isset($option['engraving_text'])){
                            $engravingText = trim($option['engraving_text']);
                            if($engravingText == ''){
                                continue;
                            }
                            $additionalOptions[] = [
                                'code'  => 'engraving_text',
                                'label'  => __('Engraving Text')->getText(),
                                'value' => $engravingText
                            ];
                            $options = $orderItem->getProductOptions();
                            $options['additional_options'] = $additionalOptions;
                            $orderItem->setProductOptions($options);
                        }
                    }
                }
            }
        }catch(\Exception $e){
            /*@todo: add code to log the error*/
        }
        return $this;
    }
}
