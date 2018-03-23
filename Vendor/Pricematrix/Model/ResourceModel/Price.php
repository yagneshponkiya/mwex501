<?php
namespace Vendor\Pricematrix\Model\ResourceModel;
use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
class Price extends AbstractDb
{    protected function _construct()    {
        $this->_init('custom_pricematrix', 'pricematrix_id');
    }
}
