<?php
namespace Vendor\Pricematrix\Ui\Component\Price\Grid\Column;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class Color implements OptionSourceInterface
{
    protected $productAttributeRepository;

    public function __construct(\Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository)
    {
        $this->productAttributeRepository = $productAttributeRepository;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
		$colorOptions = $this->productAttributeRepository->get('product_color')->getOptions();
		 
      /*  $categories = $this->categoryCollection->create()->addAttributeToSelect("*")->addFieldToFilter('is_active', 1);  */
        $options = [];
        foreach ($colorOptions as $colorOption) {
            $options[] = [
                'label' => $colorOption->getLabel(),
                'value' => $colorOption->getValue(),
            ];
        }
        return $options;
		
	}
}
