<?php

namespace ClickAndMortar\Alexa\Intent;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Reports\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Api\OrderRepositoryInterface;

class GetOrdersCountForPeriodIntent extends AbstractIntent
{
    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /** @var CollectionFactory */
    private $collectionFactory;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct();

        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        $slotsValues = $this->getSlotValues($this->slots);

        $period = $slotsValues['period']['resolved'];
        $periods = $this->getPeriods();

        if (!isset($periods[$period])) {
            return $this->getOrdersCount();
        }

        $collection = $this->collectionFactory->create()->addCreateAtPeriodFilter($periods[$period]['periodFilter']);
        $collection->calculateTotals();
        $collection->load();
        $totals = $collection->getFirstItem();

        $texts = [
            'Vous avez %d commandes pour %s.',
            'Il y a %d commandes pour %s.',
            'Le nombre de commandes est de %d pour %s.',
        ];

        return sprintf($this->getRandomText($texts), $totals->getData('quantity') * 1, $periods[$period]['periodText']);
    }

    /**
     * @return string
     */
    private function getOrdersCount(): string
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $orders = $this->orderRepository->getList($searchCriteria);

        $texts = [
            'Vous avez %d commandes depuis le lancement du site.',
            'Il y a %d commandes depuis le lancement du site.',
            'Le nombre de commandes est de %d depuis le lancement du site.',
        ];

        return sprintf($this->getRandomText($texts), $orders->getTotalCount());
    }
}
