<?php
/*
 *  File: /src/Core/Events.php                                                 *
 *  Project: dhl-status                                                        *
 *  File Created: 26-12-2021 20:24:50                                          *
 *  Author: Jan Loeper <kalte_sterne@arcor.de>                                 *
 *  -----                                                                      *
 *  Copyright (c) 2021 Jan Loeper                                              *
 *  Licensed under the MIT License.                                            *
 *  See http://www.opensource.org/licenses/MIT for details.                    *
 */


namespace Tremendo\DhlStatus\Core;

use Exception;
use \OxidEsales\Eshop\Core\Registry;
use \OxidEsales\Eshop\Core\DatabaseProvider;

class Events {

    /*
    @throws Exception
    */
    public static function onActivate() : void {
        self::addShipmentStatusColumn();
        //self::clearCache();
    }

    public static function onDeactivate() : void {
        //self::clearCache();
    }

    private static function clearCache() : void {
        $clearDir = glob('../tmp/*');
        $clearDir = array_merge( $clearDir, glob('../tmp/smarty/*') );
        foreach($clearDir as $file) {
            if(!is_dir($file)) {
                @unlink($file);
            }
        }
    }

    private static function addShipmentStatusColumn() {
        $sql = "ALTER TABLE oxorder 
            ADD COLUMN `TREMENDO_DHLSTATUS_DELIVERED` TINYINT(1) NOT NULL DEFAULT 0,
            ADD COLUMN `TREMENDO_DHLSTATUS_DATE` DATETIME,
            ADD COLUMN `TREMENDO_DHLSTATUS_MSG` VARCHAR(255)";
        try {
            DatabaseProvider::getDb()->execute($sql);
            return 1;
        } catch (Exception $e) {
            if ($e->getCode() === 1060) {
                return 0;
            }
            throw $e;
        }
    }
}
