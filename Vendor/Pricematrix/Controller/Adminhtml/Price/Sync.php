<?phpnamespace Vendor\Pricematrix\Controller\Adminhtml\Price;class Sync extends \Vendor\Pricematrix\Controller\Adminhtml\Price{    public function execute()
    {
		$storeId = 0;
		
		$pricematrixId = $this->getRequest()->getParam("pricematrix_id");
		 if ($pricematrixId) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $priceModel =  $this->_objectManager->create('Vendor\Pricematrix\Model\Price')->load($pricematrixId);
			$productCollection = $this->_objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
			$collection = $productCollection->create();			
			$collection->addCategoriesFilter(array('eq'=>$priceModel->getProductCategory()));			
			if(!empty($priceModel->getProductColor())){
				$collection->addAttributeToFilter('product_color',array('eq'=>$priceModel->getProductColor()));
			}
			
			if(!empty($priceModel->getProductStyle())){
				$collection->addAttributeToFilter('product_style',array('eq'=>$priceModel->getProductStyle()));
			}
			
			if(!empty($priceModel->getProductSquaremeter())){
				 
				if($priceModel->getProductSquaremeter()=='lteq6') {
						$collection->addAttributeToFilter('product_square_meters_text',array('lteq'=>6));
				} else if($priceModel->getProductSquaremeter()=='gteq10.01') {
						$collection->addAttributeToFilter('product_square_meters_text',array('gteq'=>10.01));
				} else {
					$productSquaremeter = @explode('-',$priceModel->getProductSquaremeter());
					
					if(is_array($productSquaremeter) && count($productSquaremeter)==2) {
						$collection->addAttributeToFilter('product_square_meters_text', array('from'=>$productSquaremeter[0],'to'=>$productSquaremeter[1]));
					}
				}
			}
			
			
			$productIds = array();
			foreach($collection as $product) {
				$productIds[$product->getId()] = ($product->getProductSquareMetersText()*$priceModel->getCustomPrice());
			}
				// TODO why use ObjectManager?
                /** @var \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry */
                $stockRegistry = $this->_objectManager
                    ->create('Magento\CatalogInventory\Api\StockRegistryInterface');
                /** @var \Magento\CatalogInventory\Api\StockItemRepositoryInterface $stockItemRepository */
                $stockItemRepository = $this->_objectManager
                    ->create('Magento\CatalogInventory\Api\StockItemRepositoryInterface');
			
				 $dataObjectHelper = $this->_objectManager->create('\Magento\Framework\Api\DataObjectHelper');
				$inventoryData = array('is_in_stock'=>1);
				$productArray= array();
				if(count($productIds)) {
					foreach($productIds as $key => $value) {
						$productArray[] = $key;
						$this->_objectManager->get('Magento\Catalog\Model\Product\Action')->updateAttributes(array($key), array('price'=>$value), 0);
						
						$productId = $key;
						$stockItemDo = $stockRegistry->getStockItem(
							$productId,
							1
						);
						if (!$stockItemDo->getProductId()) {
							$inventoryData[] = $productId;
						}

						$stockItemId = $stockItemDo->getId();
						$dataObjectHelper->populateWithArray(
							$stockItemDo,
							$inventoryData,
							'\Magento\CatalogInventory\Api\Data\StockItemInterface'
						);
						$stockItemDo->setItemId($stockItemId);
						$stockItemRepository->save($stockItemDo);
					}
				} 
				$priceModel->setLastSync(new \DateTime())->save();
			}	
		$productFlatIndexer = $this->_objectManager->create('\Magento\Catalog\Model\Indexer\Product\Flat\Processor');
		$productPriceIndexer = $this->_objectManager->create('\Magento\Catalog\Model\Indexer\Product\Price\Processor');
        
		if(count($productArray)) {
			$productPriceIndexer->reindexList($productArray);
			$productFlatIndexer->reindexList($productArray);
		}
		 $this->messageManager->addSuccessMessage(__(count($productIds).' Product(s) price  affected. Sync Completed !!!'));
        return $resultRedirect->setPath('pricematrix/price/index');
    }

}
