<?phpnamespace Vendor\Pricematrix\Controller\Adminhtml\Price;
class Edit extends \Vendor\Pricematrix\Controller\Adminhtml\Price
{
    public function execute()
    {

        $resultPage = $this->resultPageFactory->create();
        $id = $this->getRequest()->getParam('pricematrix_id');
        $model = $this->_objectManager->create('Vendor\Pricematrix\Model\Price');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This data price no longer exists.'));
                return $this->resultRedirectFactory->create()->setPath('pricematrix/price/index');
            }
        }
        $this->coreRegistry->register('price_data', $model);
        return $resultPage;
    }
}
