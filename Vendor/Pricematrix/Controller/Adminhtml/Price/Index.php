<?php
namespace Vendor\Pricematrix\Controller\Adminhtml\Price;
class Index extends \Vendor\Pricematrix\Controller\Adminhtml\Price{

    /**
     * Execute action
     */
    public function execute()
    {
		
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu("Magento_Catalog::catalog");
        $resultPage->getConfig()->getTitle()->prepend(__('PriceMatrix > Price'));
        $resultPage->addBreadcrumb(__('PriceMatrix'), __('PriceMatrix'));
        $resultPage->addBreadcrumb(__('PriceMatrix'), __('Manage PriceMatrix'));

        return $resultPage;
		
    }
}
