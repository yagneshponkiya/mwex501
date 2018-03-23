<?phpnamespace Vendor\Pricematrix\Controller\Adminhtml\Price;class Delete extends  Vendor\Pricematrix\Controller\Adminhtml\Price{
    public function execute()
    {
        $id = $this->getRequest()->getParam('pricematrix_id');
        if ($id) {
            try {
                $model = $this->_objectManager->create('Vendor\Pricematrix\Model\Price');
                $model->setId($id);
                $model->delete();
                $this->messageManager->addSuccess(__('The price data  has been deleted.'));
                return $this->resultRedirectFactory->create()->setPath('pricematrix/price/index');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $this->resultRedirectFactory->create()->setPath('pricematrix/price/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__("We can't find a price data to delete."));
        return $this->resultRedirectFactory->create()->setPath('pricematrix/pricec/index');
    }
}
