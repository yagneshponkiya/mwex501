<?phpnamespace Vendor\Pricematrix\Block\Adminhtml\Price\Edit;
use Magento\Backend\Block\Widget\Tabs as WidgetTabs;
class Tabs extends WidgetTabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('price_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Price Information'));
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'price_info',
            [
                'label' => __('General'),
                'title' => __('General'),
                'content' => $this->getLayout()->createBlock(
                    'Vendor\Pricematrix\Block\Adminhtml\Price\Edit\Tab\Info'
                )->toHtml(),
                'active' => true
            ]
        );

        return parent::_beforeToHtml();
    }
}
