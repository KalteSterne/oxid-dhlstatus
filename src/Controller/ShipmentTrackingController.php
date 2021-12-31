<?php
/*
 *  File: /src/Controller/ShipmentTrackingController.php                       *
 *  Project: dhl-status                                                        *
 *  File Created: 24-12-2021 13:16:57                                          *
 *  Author: Jan Loeper <kalte_sterne@arcor.de>                                 *
 *  -----                                                                      *
 *  Copyright (c) 2021 Jan Loeper                                              *
 *  Licensed under the MIT License.                                            *
 *  See http://www.opensource.org/licenses/MIT for details.                    *
 */


namespace Tremendo\DhlStatus\Controller;

use \OxidEsales\Eshop\Core\Registry;
use \Tremendo\DhlStatus\Model\ShipmentTracking;

class ShipmentTrackingController extends \OxidEsales\Eshop\Application\Controller\FrontendController {

    protected $_sThisTemplate = 'shipment_tracking.tpl';


    public function render() {

        parent::render();

        $user = $this->getUser();
        if (!$user) {
            return $this->_sThisTemplate = $this->_sThisLoginTemplate;
        }

        return $this->_sThisTemplate;
    }

    public function getBreadCrumb():array {

        $paths = [];
        $path = [];

        $baseLanguage = Registry::getLang()->getBaseLanguage();
        $selfLink = $this->getViewConfig()->getSelfLink();

        $path['title'] = Registry::getLang()->translateString('MY_ACCOUNT', $baseLanguage, false);
        $path['link'] = Registry::getSeoEncoder()->getStaticUrl($selfLink . 'cl=account');
        $paths[] = $path;

        $path['title'] = Registry::getLang()->translateString('ORDER_HISTORY', $baseLanguage, false);
        $path['link'] = Registry::getSeoEncoder()->getStaticUrl($selfLink . 'cl=account_order');
        $paths[] = $path;

        $path['title'] = Registry::getLang()->translateString('TREMENDO_DHLSTATUS_SHIPMENTTRACKING', $baseLanguage, false);
        $path['link'] = $this->getLink();
        $paths[] = $path;

        return $paths;
    }

    public function getTrackingData():object {
        $piececode = Registry::getConfig()->getRequestParameter('piececode');
        $orderId = Registry::getConfig()->getRequestParameter('orderId');
        $language = $this->getActiveLangAbbr();
        $shipmentTracking = new ShipmentTracking($orderId, $piececode, $language);
        $trackingData = $shipmentTracking->getTrackingData();
        return $trackingData;
    }

}
