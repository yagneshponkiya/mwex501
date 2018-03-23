<?php
namespace Vendor\Pricematrix\Controller\Adminhtml\Price;
class Save extends \Vendor\Pricematrix\Controller\Adminhtml\Price
{
    public function execute()
    {
		$isPost = $this->getRequest()->getPost();
		 if ($isPost) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $returnToEdit = (bool)$this->getRequest()->getParam('back', false);
            $priceModel =  $this->_objectManager->create('Vendor\Pricematrix\Model\Price');

            $formData = $this->getRequest()->getParam('price');
		
            $priceId = isset($formData['pricematrix_id']) ? $formData['pricematrix_id'] : null;
            if ($priceId) {
                $priceModel->load($priceId);
            }
            try {
              
                $priceModel->setData($formData);
                $priceModel->getResource()->save($priceModel);
                if ($returnToEdit) {
                    if (!$priceId) {
                        $priceId = $storeModel->getId();
                    }
                    return $resultRedirect->setPath('pricematrix/price/edit', ['id'=>$priceId]);
                } else {
                    return $resultRedirect->setPath('pricematrix/price');
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Price could not be saved.'.$e->getMessage()));
                return $resultRedirect->setPath('pricematrix/price/edit', ['id'=>$priceId]);
            }
        }
        return $resultRedirect->setPath('pricematrix/price/index');
    }

}
