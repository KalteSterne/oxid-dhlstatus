<?php
/*
 *  File: /metadata.php                                                        *
 *  Project: dhl-status                                                        *
 *  File Created: 26-12-2021 20:24:50                                          *
 *  Author: Jan Loeper <kalte_sterne@arcor.de>                                 *
 *  -----                                                                      *
 *  Copyright (c) 2021 Jan Loeper                                              *
 *  Licensed under the MIT License.                                            *
 *  See http://www.opensource.org/licenses/MIT for details.                    *
 */


$sMetadataVersion = '2.1';

$aModule = [
    'id'           => 'tremendo_dhlstatus',
    'title'        => 'DHL-Status',
    'description'  => [
        'de' => '',
        'en' => 'DHL Shipment Status module for OXID eShop'
    ],
    'thumbnail'    => 'logo.png',
    'version'      => '0.5',
    'author'       => 'Jan Loeper',
    'email'        => 'kalte_sterne@arcor.de',
    'url'          => 'https://github.com/KalteSterne/oxid-dhlstatus',
    'blocks' => [
        [
            'template' => 'page/account/order.tpl',
            'block' => 'account_order_history', 
            'file' => 'Application/views/blocks/account_order_history.tpl', 
        ],
    ],
    'templates' => [
        'shipment_tracking.tpl' => 'tremendo/dhlstatus/Application/views/tpl/shipment_tracking.tpl' 
    ],
    'extend' => [
        \OxidEsales\Eshop\Application\Model\Order::class => \Tremendo\DhlStatus\Model\Order::class,
    ],
    'settings' => [
        [
            'group' => 'tremendo_dhlstatus_main',
            'name' => 'tremendo_dhlstatus_endpoint',
            'type' => 'str',
            'value' => 'https://api-eu.dhl.com/track/shipments'
        ],
        [
            'group' => 'tremendo_dhlstatus_main',
            'name' => 'tremendo_dhlstatus_apikey',
            'type' => 'str',
            'value' => ''
        ],
    ],
    'events' => [
        'onActivate' => '\Tremendo\DhlStatus\Core\Events::onActivate',
        'onDeactivate' => '\Tremendo\DhlStatus\Core\Events::onDeactivate' 
    ],
    'controllers'  => [
        'tremendo_dhlstatus_shipmenttracking' => \Tremendo\DhlStatus\Controller\ShipmentTrackingController::class
    ]
];