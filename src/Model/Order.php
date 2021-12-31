<?php
/*
 *  File: /src/Model/Order.php                                                 *
 *  Project: dhl-status                                                        *
 *  File Created: 24-12-2021 13:16:57                                          *
 *  Author: Jan Loeper <kalte_sterne@arcor.de>                                 *
 *  -----                                                                      *
 *  Copyright (c) 2021 Jan Loeper                                              *
 *  Licensed under the MIT License.                                            *
 *  See http://www.opensource.org/licenses/MIT for details.                    *
 */


namespace Tremendo\DhlStatus\Model;
class Order extends Order_parent {

    /*
    @return string
    */
    public function getShipmentTrackingUrl() : string {
        $trackingCode = $this->getTrackCode();
        $orderId = $this->getId();
        return "/index.php?cl=tremendo_dhlstatus_shipmenttracking&piececode=".$trackingCode."&orderId=".$orderId;
    }
    
}
