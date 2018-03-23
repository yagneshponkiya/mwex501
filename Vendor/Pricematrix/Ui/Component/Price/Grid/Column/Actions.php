<?php
namespace Vendor\Pricematrix\Ui\Component\Price\Grid\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

/**
 * Class ProductActions
 */
class Actions extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $storeId = $this->context->getFilterParam('store_id');

            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'pricematrix/price/edit',
                        ['pricematrix_id' => $item['pricematrix_id']]
                    ),
                    'label' => __('Edit'),
                    'hidden' => false,
                ];
				
				$item[$this->getData('name')]['sync'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'pricematrix/price/sync',
                        ['pricematrix_id' => $item['pricematrix_id']]
                    ),
                    'label' => __('Sync Now'),
                    'hidden' => false,
                ];
				
					$item[$this->getData('name')]['delete'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'pricematrix/price/delete',
                        ['pricematrix_id' => $item['pricematrix_id']]
                    ),
                    'label' => __('Delete'),
                    'hidden' => false,
                ];
            }
        }

        return $dataSource;
    }
}
