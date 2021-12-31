<?php
/*
 *  File: /src/Core/ModuleLogger.php                                           *
 *  Project: dhl-status                                                        *
 *  File Created: 12-03-2021 10:45:46                                          *
 *  Author: Jan Loeper <kalte_sterne@arcor.de>                                 *
 *  -----                                                                      *
 *  Copyright (c) 2021 Jan Loeper                                              *
 *  Licensed under the MIT License.                                            *
 *  See http://www.opensource.org/licenses/MIT for details.                    *
 */

namespace Tremendo\DhlStatus\Core;

use \OxidEsales\Eshop\Core\Registry;
use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;
use Psr\Log\LogLevel;

/**
 * Class ModuleLogger
 *
 * @package Tremendo\Hcaptcha\Core
 */
class ModuleLogger {
    

    protected static $logger;
    
    /**
     * @param  string $path
     * @return \Monolog\Logger
     */
    public static function getLogger(string $filename = '') : Logger {
        $filename = ((!empty($filename)) ? $filename : 'oxideshop.log');
        $path = Registry::getConfig()->getLogsDir() . $filename;
        self::$logger = new Logger('tremendo-dhlstatus');
        self::$logger->pushHandler(
            new StreamHandler($path, LogLevel::INFO)
        );
        return self::$logger;
    }
}