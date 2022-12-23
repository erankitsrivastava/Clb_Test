<?php

namespace Clb\Test\Observer\Checkout;

use \Clb\Test\Helper\Data as CustomHelper;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class CartAddAfter
 * @package Clb\Test\Observer\Checkout
 */
class CartAddAfter implements ObserverInterface
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
     * @param \Magento\Framework\App\Request\Http $request
     * @param CustomHelper $_customHelper
     * @param \Magento\Framework\Serialize\Serializer\Json $serialize
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        CustomHelper $_customHelper,
        \Magento\Framework\Serialize\Serializer\Json $serialize
    ) {
        $this->request = $request;
        $this->_customHelper = $_customHelper;
        $this->serialize = $serialize;
    }

    /**
     * @param Observer $observer
     * @return $this|void
     */
    public function execute(Observer $observer) {

        $postValue = $this->request->getPostValue();

        $item = $observer->getEvent()->getData('quote_item');
        try{
            $product = $item->getProduct();
            if(!$this->_customHelper->canShowEngravingonPDP($product)) {
                return $this;
            }

            if (isset($postValue['has_engraving']) &&
                isset($postValue['engraving_text'])  &&
                $postValue['has_engraving'] == 'on'){

                $engravingText = trim($postValue['engraving_text']);
                if($engravingText == ''){
                    return $this;
                }
                $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
                $additionalOptions = [
                    [
                        'code'  => 'engraving_text',
                        'label'  => __('Engraving Text')->getText(),
                        'value' => $engravingText
                    ]
                ];
                $serializedOptions = $this->serialize->serialize($additionalOptions);

                $item->addOption(
                    new \Magento\Framework\DataObject(
                        [
                            'product' => $item->getProduct(),
                            'code' => 'additional_options',
                            'value' => $serializedOptions
                        ]
                    )
                );
            }
        }catch(\Exception $e){
        }
    }
}
