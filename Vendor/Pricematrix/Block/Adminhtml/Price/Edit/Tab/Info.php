<?php
namespace Vendor\Pricematrix\Block\Adminhtml\Price\Edit\Tab;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory; 
class Info extends Generic implements TabInterface
{
	protected $categoryCollection;
	protected $productAttributeRepository;
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
		\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollection,
		\Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository,
        array $data = []
    ) {
		$this->productAttributeRepository = $productAttributeRepository;
		$this->categoryCollection = $categoryCollection;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $model  = $this->_coreRegistry->registry('price_data');
		
        $form   = $this->_formFactory->create();
        $form->setHtmlIdPrefix('price_');
        $form->setFieldNameSuffix('price');


        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General')]
        );
        if ($model->getId()) {
            $fieldset->addField(
                'pricematrix_id',
                'hidden',
                ['name' => 'pricematrix_id']
            );
        } 
		$categories = $this->categoryCollection->create()->addAttributeToSelect("*")->addAttributeToSort('path', 'asc')->addFieldToFilter('is_active', 1);
		 
		$categoryArray = array();
		   foreach ($categories as $category) {
				$str = str_repeat('--',$category->getLevel());
				$categoryArray[$category->getId()] = $str.$category->getName();
           }
         
        $fieldset->addField(
            'product_category',
            'select',
            [
                'name'        => 'product_category',
                'label'    => __('Product Category'),
                'required'     => true,
				'options'   => $categoryArray
            ]
        );
		
		
		$productSquareMeter = array(
		"0-1" =>"0-1",
		"1.01-2" =>"1.01-2",
		"2.01-3" =>"2.01-3",
		"3.01-4" =>"3.01-4",
		"4.01-5"=>"4.01-5",
		"5.01-6"=>"5.01-6",
		"6.01-7"=>"6.01-7",
		"7.01-8"=>"7.01-8",
		"8.01-10"=>"8.01-10",
		"gteq10.01"=>">=10.01");

        $fieldset->addField(
            'product_squaremeter',
            'select',
            [
                'name'        => 'product_squaremeter',
                'label'    => __('Product Square Meters'),
                'required'     => false,
				'options'   => $productSquareMeter
            ]
        );
		
		
		$productColors = $this->productAttributeRepository->get('product_color')->getOptions();
		$colorArray = array();
		foreach($productColors as $productcolor) {
			$colorArray[$productcolor->getValue()] = $productcolor->getLabel();
		}

        $fieldset->addField(
            'product_color',
            'select',
            [
                'name'        => 'product_color',
                'label'    => __('Product Color'),
                'required'     => false,
				'options'   => $colorArray
            ]
        );
		
		$productStyles = $this->productAttributeRepository->get('product_style')->getOptions();
		$styleArray = array();
		foreach($productStyles as $productStyle) {
			$styleArray[$productStyle->getValue()] = $productStyle->getLabel();
		}

        $fieldset->addField(
            'product_style',
            'select',
            [
                'name'        => 'product_style',
                'label'    => __('Product Style'),
                'required'     => false,
				'options'   => $styleArray
            ]
        );
		
		 $fieldset->addField(
            'custom_price',
            'text',
            [
                'name'        => 'custom_price',
                'label'    => __('Product Price (IN USD)'),
                'required'     => true,
				'value'=>10
            ]
        );
        $data = $model->getData();
        $form->setValues($data);
     $this->setForm($form);
		

        return parent::_prepareForm();
    }
    public function getTabLabel()
    {
        return __('Store Info');
    }
    public function getTabTitle()
    {
        return __('Store Info');
    }
    public function canShowTab()
    {
        return true;
    }
    public function isHidden()
    {
        return false;
    }
}
