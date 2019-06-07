<?php

namespace ClickAndMortar\Alexa\Intent;

abstract class AbstractIntent implements IntentInterface
{
    /** @var array */
    protected $slots;

    public function __construct()
    {
        $this->slots = [];
    }

    /**
     * @inheritDoc
     */
    public function setSlots(array $slots): void
    {
        $this->slots = $slots;
    }

    /**
     * Function is a rewrite of Javascript generated code
     * from https://s3.amazonaws.com/webappvui/skillcode/v2/index.html
     *
     * @param array $filledSlots
     * @return array
     */
    protected function getSlotValues(array $filledSlots): array
    {
        $slotValues = [];

        foreach ($filledSlots as $item) {
            $name = $item['name'];

            if ($item &&
                isset($item['resolutions']['resolutionsPerAuthority'][0]['status']['code'])
            ) {
                $resolutions = $item['resolutions'];

                switch ($item['resolutions']['resolutionsPerAuthority'][0]['status']['code']) {
                    case 'ER_SUCCESS_MATCH':
                        $slotValues[$name] = [
                            'heardAs' => $item['value'],
                            'resolved' => $resolutions['resolutionsPerAuthority'][0]['values'][0]['value']['name'],
                            'ERstatus' => 'ER_SUCCESS_MATCH'
                        ];
                        break;
                    case 'ER_SUCCESS_NO_MATCH':
                        $slotValues[$name] = [
                            'heardAs' => $item['value'],
                            'resolved' => '',
                            'ERstatus' => 'ER_SUCCESS_NO_MATCH'
                        ];
                        break;
                    default:
                        break;
                }
            } else {
                $slotValues[$name] = [
                    'heardAs' => '',
                    'resolved' => '',
                    'ERstatus' => ''
                ];
            }
        }

        return $slotValues;
    }

    /**
     * @param array $texts
     * @return string
     */
    protected function getRandomText(array $texts): string
    {
        return $texts[array_rand($texts)];
    }

    /**
     * @return array
     */
    protected function getPeriods(): array
    {
        $periods = [
            'dernières vingt-quatre heures' => [
                'periodFilter' => '24h', // Period is last 24 hours and next hour
                'periodText' => 'les dernières vingt-quatre heures'
            ],
            'sept derniers jours' => [
                'periodFilter' => '7d', // Period is last 6 days and current day
                'periodText' => 'les sept derniers jours'
            ],
            'mois' => [
                'periodFilter' => '1m', // Period is current month
                'periodText' => 'le mois en cours'
            ],
        ];

        return $periods;
    }
}
