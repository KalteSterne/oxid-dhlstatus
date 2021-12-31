<?php
/*
 *  File: /src/Model/ShipmentTracking.php                                      *
 *  Project: dhl-status                                                        *
 *  File Created: 26-12-2021 20:24:50                                          *
 *  Author: Jan Loeper <kalte_sterne@arcor.de>                                 *
 *  -----                                                                      *
 *  Copyright (c) 2021 Jan Loeper                                              *
 *  Licensed under the MIT License.                                            *
 *  See http://www.opensource.org/licenses/MIT for details.                    *
 */

namespace Tremendo\DhlStatus\Model;

use \OxidEsales\Eshop\Core\Registry;
use \OxidEsales\Eshop\Core\Field as DbField;
use Tremendo\DhlStatus\Core\ModuleLogger;

class ShipmentTracking {
    
    /*
    @var \stdClass $trackingData
    */
    private $trackingData;

    /*
    @var \OxidEsales\Eshop\Application\Model\Order $order
    */
    protected $order;

    /*
    @param string $orderId
    @param string piececode
    @param string language
    */
    public function __construct(string $orderId, string $piececode, string $language) {
        $this->order = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $this->order->load($orderId);
        
        $this->trackingData = new \stdClass();

        if ($this->order->oxorder__tremendo_dhlstatus_delivered->value == 0) {
            // only get tracking data from DHL if the shipment if not already marked as delivered
            $this->loadFromDhl($piececode, $language);
        } else {
            // get delivery date and success message from the database
            $this->loadFromDb();
        }
    }

    /*
    @param string piececode
    @param string language
    */
    private function loadFromDhl(string $piececode, string $language) : void {
        $endpoint = Registry::getConfig()->getConfigParam('tremendo_dhlstatus_endpoint');
        $apiKey = Registry::getConfig()->getConfigParam('tremendo_dhlstatus_apikey');
        $zip = $this->order->oxorder__oxdelzip;

        $client = new \GuzzleHttp\Client();
        $url = $endpoint.
            '?trackingNumber='.$piececode.
            '&language='.$language.
            '&recipientPostalCode='.$zip;
        try {
            $response = $client->get($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'DHL-API-Key' => $apiKey
                ],
            ]);
        } catch (\GuzzleHttp\RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
            } else {
                $logger = ModuleLogger::getLogger('tremendo-dhltracking.log');
                $logger->error($e);
            }
        }

        if (!isset($response)) {
            $this->trackingData->status = 'error';
            $this->trackingData->statusMsg = 'fatal';
        } elseif ($response->getStatusCode != '200') {
            $this->trackingData->status = 'error';
            $this->trackingData->statusMsg = $response->getReasonPhrase();
        } else {
            $this->trackingData->status = 'success';
            $decoded = json_decode($response->getBody(), false);
            $this->trackingData->events = $decoded->shipments[0]->events; 
            if ($this->trackingData->events[0]->statusCode == 'delivered') {
                $this->save();
            }
        }
    }

    private function loadFromDb() : void {
        $this->trackingData->events[0]->statusCode =
            ($this->order->oxorder__tremendo_dhlstatus_delivered->value == 1) ? 'delivered' : '';
        $this->trackingData->events[0]->description = 
            $this->order->oxorder__tremendo_dhlstatus_msg->value;
        $this->trackingData->events[0]->timestamp = 
            $this->order->oxorder__tremendo_dhlstatus_date->value;
    }

    private function save() : void {
        $this->order->oxorder__tremendo_dhlstatus_delivered = new DbField(1);
        $this->order->oxorder__tremendo_dhlstatus_msg = new DbField(
            (string)$this->trackingData->events[0]->status
        );
        $this->order->oxorder__tremendo_dhlstatus_date = new DbField(
            $this->trackingData->events[0]->timestamp
        );
        $this->order->save(); 
    }

    /*
    @return \stdClass
    */
    public function getTrackingData() : \stdClass {
        return $this->trackingData;
    }

}
