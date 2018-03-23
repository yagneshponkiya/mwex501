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
            $options[] = [
                'label' => $category->getName(),
                'value' => $category->getId(),
            ];
        }
        return $options;
    }
}
