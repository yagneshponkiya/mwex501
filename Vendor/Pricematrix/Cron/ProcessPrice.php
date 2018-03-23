<?php
namespace Vendor\Pricematrix\Cron;
class ProcessPrice
{
	
	protected $storeManager;
	
	protected $_scopeConfig;
	
	protected $_config;
	
	protected $_reinitConfig;	
	public function __construct(\Magento\Store\Model\StoreManagerInterface $storeManager,
	\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
	\Magento\Config\Model\ResourceModel\Config $config,
    \Magento\Framework\App\Config\ReinitableConfigInterface $reinitconfig
	){
	//	$this->_priceCollection = $priceCollection;
		$this->_reinitConfig = $reinitconfig;
		$this->_config = $config;
		$path = "cataloginventory/options/show_out_of_stock";
		$this->_config->saveConfig($path,1,'default','0');
		$this->_reinitConfig->reinit();
			
		$this->_scopeConfig = $scopeConfig;
		$this->storeManager = $storeManager;
	}
	
	public function __destruct(){
		//echo "descruct called";
		$path = "cataloginventory/options/show_out_of_stock";
		
		$this->_config->saveConfig($path,0,'default','0');
		$this->_reinitConfig->reinit();
			
	}

    /**
     * Execute action
     * @return void
     */
	public function execute()
    {
			
			
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	  
		$priceCollectionObject = $objectManager->create('\Vendor\Pricematrix\Model\ResourceModel\Price\CollectionFactory');
		
		$priceCollection = $priceCollectionObject->create();
		$cntr = 0;
		foreach($priceCollection as $priceModel) {
				
			//if($priceModel->getId()==12) 
			//{	
									
				$productCollection = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
				
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
						
						if(is_array($productSquaremeter)  && count($productSquaremeter)==2) {
							
							$collection->addAttributeToFilter('product_square_meters_text', array('from'=>$productSquaremeter[0],'to'=>$productSquaremeter[1]));
						}
					}
				}
					$collection->joinField('stock_item', 'cataloginventory_stock_item', 'is_in_stock', 'product_id=entity_id', 'is_in_stock=0');
					
					
									
				$productIds = array();
				foreach($collection as $product) {
					
					$productIds[$product->getId()] = ($product->getProductSquareMetersText()*$priceModel->getCustomPrice());
					
				}
				
				
						
				// TODO why use ObjectManager?
				/** @var \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry */
				$stockRegistry = $objectManager->create('Magento\CatalogInventory\Api\StockRegistryInterface');
				/** @var \Magento\CatalogInventory\Api\StockItemRepositoryInterface $stockItemRepository */
				$stockItemRepository = $objectManager->create('Magento\CatalogInventory\Api\StockItemRepositoryInterface');
		
				$dataObjectHelper = $objectManager->create('\Magento\Framework\Api\DataObjectHelper');
				$inventoryData = array('is_in_stock'=>1);
		
				$productArray= array();
				if(count($productIds)) {
					foreach($productIds as $key => $value) {
						$productArray[] = $key;
						$objectManager->get('Magento\Catalog\Model\Product\Action')->updateAttributes(array($key), array('price'=>$value), 0);
						
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
				
				$productFlatIndexer = $objectManager->create('\Magento\Catalog\Model\Indexer\Product\Flat\Processor');
				$productPriceIndexer = $objectManager->create('\Magento\Catalog\Model\Indexer\Product\Price\Processor');
				
				if(count($productArray)) {
					$productPriceIndexer->reindexList($productArray);
					//$this->_stockIndexerProcessor->reindexList($productArray);
					$productFlatIndexer->reindexList($productArray);
				}
			//}	
		}
		
    }

}
