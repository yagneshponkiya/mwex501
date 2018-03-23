<?php
namespace Vendor\Pricematrix\Ui\Component\Price\Grid\Column;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class Category implements OptionSourceInterface
{
    protected $categoryCollection;

    public function __construct(\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollection)
    {
        $this->categoryCollection = $categoryCollection;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $categories = $this->categoryCollection->create()->addAttributeToSelect("*")->addFieldToFilter('is_active', 1);
        $options = [];
        foreach ($categories as $category) {
			
			$pathIds = explode('/', $category->getPath()); 
			unset($pathIds[0]);
			unset($pathIds[1]);
			 $collection = $this->categoryCollection->create()->addAttributeToSelect("*")
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('is_active')
            ->addFieldToFilter('entity_id', array('in' => $pathIds));

			 $pahtByName = '';
				foreach($collection as $cat){                
					$pahtByName .= '/' . $cat->getName();
				}
			
            $options[] = [
                'label' => $pahtByName,
                'value' => $category->getId(),
            ];
        }
        return $options;
    }
}
