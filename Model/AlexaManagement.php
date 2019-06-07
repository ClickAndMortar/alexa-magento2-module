<?php

namespace ClickAndMortar\Alexa\Model;

use ClickAndMortar\Alexa\Api\AlexaApplicationInterface;
use ClickAndMortar\Alexa\Factory\IntentFactory;
use ClickAndMortar\Alexa\Intent\IntentInterface;

class AlexaManagement implements AlexaApplicationInterface
{
    /** @var AlexaResponseFactory */
    private $responseDataFactory;

    /** @var IntentFactory */
    private $intentFactory;

    public function __construct(
        AlexaResponseFactory $responseDataFactory,
        IntentFactory $intentFactory
    ) {
        $this->responseDataFactory = $responseDataFactory;
        $this->intentFactory = $intentFactory;
    }

    /**
     * @inheritdoc
     */
    public function launchRequest(): AlexaResponse
    {
        $response = $this->responseDataFactory->create();
        $response->setOutputSpeech('Que voulez-vous savoir à propos du site Magento 2 ?');
        $response->setShouldEndSession(false);

        return $response;
    }

    /**
     * @inheritdoc
     */
    public function intentRequest(array $request): AlexaResponse
    {
        $response = $this->responseDataFactory->create();
        $this->setOutputSpeech($response, $request['intent']);

        return $response;
    }

    /**
     * @inheritdoc
     */
    public function sessionEndedRequest(string $reason): AlexaResponse
    {
        $response = $this->responseDataFactory->create();
        $response->setOutputSpeech('A bientôt.');

        return $response;
    }

    /**
     * @param AlexaResponse $response
     * @param array $intentData
     */
    private function setOutputSpeech(AlexaResponse $response, array $intentData): void
    {
        $slots = $intentData['slots'] ?? [];

        $intent = $this->intentFactory->create($intentData['name'], $slots);
        if ($intent instanceof IntentInterface) {
            $response->setOutputSpeech($intent->getText());
        }
    }
}
