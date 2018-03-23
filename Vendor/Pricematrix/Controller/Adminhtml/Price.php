<?php
namespace Vendor\Pricematrix\Controller\Adminhtml;
abstract class Price extends \Magento\Backend\App\Action
{

    public $coreRegistry = null;
    public $resultForwardFactory = null;
    public $resultRawFactory = null;	
    public $resultPageFactory = null;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
    ) {
		$this->coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultRawFactory = $resultRawFactory;
        
        parent::__construct($context);
    }

    /**
     * Does the menu is allowed
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Vendor_Pricematrix::main');
    }

    /**
     * execute action
     */
    abstract public function execute();
}
