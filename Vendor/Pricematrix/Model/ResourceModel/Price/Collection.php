<?phpnamespace Vendor\Pricematrix\Model\ResourceModel\Price;class Collection extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
    protected function _construct()
    {
        $this->_init(
            'Vendor\Pricematrix\Model\Price',
            'Vendor\Pricematrix\Model\ResourceModel\Price'
        );
    }
}
