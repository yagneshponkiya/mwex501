<?php
namespace Vendor\Pricematrix\Model;

class Price extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('\Vendor\Pricematrix\Model\ResourceModel\Price');
    }
}
