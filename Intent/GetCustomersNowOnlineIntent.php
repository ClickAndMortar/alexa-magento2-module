<?php

namespace ClickAndMortar\Alexa\Intent;

use Magento\Customer\Model\ResourceModel\Online\Grid\Collection as OnlineGridCollection;

class GetCustomersNowOnlineIntent extends AbstractIntent
{
    /**
     * @var OnlineGridCollection
     */
    protected $onlineGridCollection;

    public function __construct(
        OnlineGridCollection $onlineGridCollection
    ) {
        parent::__construct();

        $this->onlineGridCollection = $onlineGridCollection;
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        $online = $this->onlineGridCollection;

        $texts = [
            'Le nombre de clients et visiteurs en ligne est de %d.'
        ];

        return sprintf($this->getRandomText($texts), $online->getSize());
    }
}
