<?php

namespace ClickAndMortar\Alexa\Api;

use ClickAndMortar\Alexa\Model\AlexaResponse;

interface AlexaApplicationInterface
{
    /**
     * @return AlexaResponse
     */
    public function launchRequest(): AlexaResponse;

    /**
     * @param array $request
     * @return AlexaResponse
     */
    public function intentRequest(array $request): AlexaResponse;

    /**
     * @param string $reason
     * @return AlexaResponse
     */
    public function sessionEndedRequest(string $reason): AlexaResponse;
}
