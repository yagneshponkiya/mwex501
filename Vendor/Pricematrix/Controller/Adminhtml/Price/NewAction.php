<?php
namespace Vendor\Pricematrix\Controller\Adminhtml\Price;
    public function execute()
    {
        return $this->resultForwardFactory->create()->forward("edit");
    }
}